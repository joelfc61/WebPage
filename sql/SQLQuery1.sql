create database oxxo;

use oxxo;

create table tbl_usuario(
	id smallint identity(1,1) not null primary key,
	usuario varchar(20) not null,
	psw varchar(255) not null,
	fecha_login datetime,
	fecha_logout datetime,
	id_persona smallint not null,
	foreign key (id_persona) references tbl_persona(id)
);

create table tbl_persona(
	id smallint identity(1,1) not null primary key,
	nombre varchar(30) not null,
	a_paterno varchar(40) not null,
	a_materno varchar(40) not null,
	telefono varchar(15) not null
);

create table tbl_refrendo(
	id smallint not null primary key,
	saldo float not null,
	id_persona smallint not null,
	fecha datetime,
	foreign key (id_persona) references tbl_persona(id)
);

create table clt_proveedor_telefonia(
	id tinyint not null identity(1,1) primary key,
	nombre varchar(50) not null,
	descripcion varchar(50),
	impuestos float not null
);

create table tbl_telefonia(
	id bigint not null primary key,
	id_proveedor tinyint not null,
	saldo float not null,
	id_persona smallint not null,
	foreign key(id_persona) references tbl_persona(id)
);

create table tbl_movimiento_telefonia(
	id int not null identity(1,1) primary key,
	id_telefonia bigint not null,
	monto float not null,
	id_uso tinyint not null,
	fecha datetime not null,
	tiempo float,
	foreign key(id_telefonia) references tbl_telefonia(id),
	foreign key(id_uso) references clt_uso(id)
);

create table clt_tipo_movimiento(
	id tinyint not null identity(1,1) primary key,
	tipo varchar(50) not null,
	descripcion varchar(50)
);

create table clt_uso(
	id tinyint not null identity(1,1) primary key,
	id_proveedor tinyint not null,
	id_tipo_movimiento tinyint not null,
	id_telefonia bigint not null,
	costo float not null,
	foreign key(id_proveedor) references clt_proveedor_telefonia(id),
	foreign key(id_tipo_movimiento) references clt_tipo_movimiento(id),
	foreign key(id_telefonia) references tbl_telefonia(id)
);