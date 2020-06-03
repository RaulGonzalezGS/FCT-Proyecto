create table pasirusuarios
(
    usuario    varchar(191) not null,
    contrasena varchar(255) not null,
    perfil     varchar(255) null,
    constraint pasirusuarios_usuario_uindex
        unique (usuario)
);

INSERT INTO pasir.pasirusuarios (usuario, contrasena, perfil) VALUES ('frodo', '3c3f117e32f0459c1370ee4b8fa74b743827a5aa5e7c51069ea09f9fac0359319dab2f334b94481a968094a2d3971ceb4c7f7b90fbe4d598d961147caaf55051', 'Admin');
INSERT INTO pasir.pasirusuarios (usuario, contrasena, perfil) VALUES ('prueba', '6c4e454404d2445cd74bdd28a1edb9219c66398f3969058021ee075908b86c3a4b4cb0de6c53315b6a1d3c81491ee0251d8760ab15280b58882c5ba65396b82a', null);
