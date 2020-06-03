create table pasiraccper
(
    accion varchar(255) not null,
    perfil varchar(255) not null
);

INSERT INTO pasir.pasiraccper (accion, perfil) VALUES ('Establecer parametros del contenedor de Docker', 'Docker');
INSERT INTO pasir.pasiraccper (accion, perfil) VALUES ('Abastecer de Alta Disponibilidad', 'HA');
INSERT INTO pasir.pasiraccper (accion, perfil) VALUES ('Desplegar microservicios en infraestructura de Docker', 'Servicios');
INSERT INTO pasir.pasiraccper (accion, perfil) VALUES ('Establecer parametros del contenedor de Docker', 'Admin');
INSERT INTO pasir.pasiraccper (accion, perfil) VALUES ('Abastecer de Alta Disponibilidad', 'Admin');
INSERT INTO pasir.pasiraccper (accion, perfil) VALUES ('Desplegar microservicios en infraestructura de Docker', 'Admin');
