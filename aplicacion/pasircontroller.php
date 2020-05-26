<?php

    $datos = file_get_contents('php://input');
    $descodificardatos = json_decode($datos, true);

    $dbconn = new mysqli("localhost", "frodo", "bolson", "pasir");

    if ($descodificardatos["cont"] == "validarcred") {

        $contrasenaenc = hash("sha3-512", $descodificardatos["contrasena"]);
        $contrasena = $dbconn->query("select contrasena from pasirusuarios where usuario = '{$descodificardatos["usuario"]}'");

        if ($contrasena->fetch_array()[0] == $contrasenaenc) {
            $descodificardatos["mensaje"] = "Usuario validado";

            session_start();
            $_SESSION['user'] = $descodificardatos["usuario"];
        }

        $resultado = json_encode($descodificardatos);
        echo $resultado;
    }

    if ($descodificardatos["cont"] == "sesionusu") {

        session_start();

        if (isset($_SESSION['user'])) {
            $descodificardatos["usuariosesion"] = $_SESSION['user'];
        }
        else {
            $descodificardatos["mensaje"] = "Sesion cerrada";
        }

        $resultado = json_encode($descodificardatos);
        echo $resultado;
    }

    if ($descodificardatos["cont"] == "cerrarsesion") {

        session_start();
        session_destroy();
    }

    if ($descodificardatos["cont"] == "enviarparametros") {

        $perfiles = $dbconn->query("select perfil from pasirusuarios where usuario = '{$descodificardatos["usuarioses"]}';");
        $perfil = $perfiles->fetch_array()[0];

        if ($perfil == null) {
            $descodificardatos["mensaje"] = "No tienes perfil de usuario";
        }
        else {

            $acciones = $dbconn->query("select accion from pasiraccper where perfil = '$perfil';");
            $listaacc = array();
            while ($row = $acciones->fetch_array()[0]) {
                array_push($listaacc, $row);
            }

            if (count($listaacc) == 0){
                $descodificardatos["mensaje"] = "No tienes accion en tu perfil de usuario";
            }
            else {
                $Docker = "No valido";
                $Servicios = "No valido";
                $HA = "No valido";
                $accionprim = "Accion no validada";
                $accionsec = "Accion no validada";
                $accionter = "Accion no validada";

                for ($i = 0; $i < count($listaacc); $i++){

                    if ($descodificardatos["Dockerdir"] == "") {
                        $Docker = "Valido";
                    }
                    else {
                        if (($listaacc[$i] == "Establecer parametros del contenedor de Docker") && ($accionprim == "Accion no validada")){
                            $Docker = "Valido";
                            $accionprim = "Accion validada";
                        }
                    }

                    if (($listaacc[$i] == "Desplegar microservicios en infraestructura de Docker") && ($accionsec == "Accion no validada")){
                        $Servicios = "Valido";
                        $accionsec = "Accion validada";
                    }

                    if (($descodificardatos["HAorquestador"] == "") && ($descodificardatos["HAreplicas"] == "")) {
                        $HA = "Valido";
                    }
                    else {
                        if (($listaacc[$i] == "Abastecer de Alta Disponibilidad") && ($accionter == "Accion no validada")){
                            $HA = "Valido";
                            $accionter = "Accion validada";
                        }
                    }
                }

                if (($Docker == "Valido") && ($Servicios == "Valido") && ($HA == "Valido")) {

                    if ($descodificardatos["Dockerdir"] == "") {
                        $descodificardatos["Dockerdir"] = "Por_defecto";
                    }

                    if (($descodificardatos["HAorquestador"] == "") && ($descodificardatos["HAreplicas"] == "")) {
                        $descodificardatos["HAorquestador"] = "Sin_HA";
                        $descodificardatos["HAreplicas"] = "Sin_HA";
                    }

                    exec("ssh -i /var/www/.ssh/rootllaveadmin root@192.168.20.15 '/root/scriptdesmicser {$descodificardatos["Servicios"]} {$descodificardatos["Dockerdir"]} {$descodificardatos["HAorquestador"]} {$descodificardatos["HAreplicas"]}'");

                    $descodificardatos["mensaje"] = "El servicio se ha desplegado correctamente";
                }
                else {
                    $descodificardatos["mensaje"] = "No tienes permiso para llevar a cabo la accion";
                }
            }
        }


        $resultado = json_encode($descodificardatos);
        echo $resultado;
    }
