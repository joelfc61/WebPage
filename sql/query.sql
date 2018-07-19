create procedure SP_Alta_Saldo(@NumTel bigint, @idTelefonia tinyint, @monto float)
as
begin
declare @countMove int;
declare @countUso int;
declare @countTelefonia int;
declare @montoTotal float;
declare @countUso2 int;
declare @idUso int;
declare @countMove2 int;
declare @montoActual float;
declare @montoNuevo float;
declare @countTelefonia2 int;
declare @idUser smallint;
declare @montoTotal2 float;
begin transaction
if (select count(*) from tbl_telefonia where id = @NumTel)>0
begin
set @countMove = (select count(*) from tbl_movimiento_telefonia);
set @countUso = (select count(*) from clt_uso);
set @montoTotal = (select saldo from tbl_telefonia);
insert into clt_uso values(@idTelefonia, 1, 0, @NumTel)
set @countUso2 = (select count(*) from clt_uso);
if (@countUso<@countUso2)
begin
set @idUso = (select MAX(id) from clt_uso);
insert into tbl_movimiento_telefonia values(@NumTel, @monto, @idUso, GETDATE(), 0)
set @countMove2 = (select count(*) from tbl_movimiento_telefonia);
if(@countMove<@countMove2)
begin
set @montoActual = (select saldo from tbl_telefonia);
set @montoNuevo = @montoActual+@monto;
set @idUser = (select id from tbl_persona where telefono = @NumTel);
update tbl_telefonia set saldo = @montoNuevo where id = @NumTel;
set @montoTotal2 = (select saldo from tbl_telefonia);
if(@montoTotal<@montoTotal2)
begin
commit transaction
select 1 result;
end
else
begin
rollback transaction
select 0 as result;
end
end
else
begin
rollback transaction
select 0 as result;
end
end
else
begin
rollback transaction 
select 0 as result;
end
end
else
begin
rollback transaction 
select 0 as result;
end
end

create procedure SP_Uso_Tel(@tipoUso bigint, @idTelefonia tinyint, @tiempo float, @NumTel bigint)
as
begin
declare @countMove int;
declare @countUso int;
declare @countTelefonia int;
declare @montoTotal float;
declare @countUso2 int;
declare @idUso int;
declare @countMove2 int;
declare @montoActual float;
declare @montoNuevo float;
declare @countTelefonia2 int;
declare @idUser smallint;
declare @montoTotal2 float;
declare @constoTran float;
declare @costoTranFinal float;
declare @impuestos float;
declare @impuestosFinal float;
declare @finalCosto float;
begin transaction
if (select count(*) from tbl_telefonia where id = @NumTel)>0
begin
set @countMove = (select count(*) from tbl_movimiento_telefonia);
set @countUso = (select count(*) from clt_uso);
set @montoTotal = (select saldo from tbl_telefonia);
set @constoTran = (select costo from clt_tipo_movimiento where id = @tipoUso)
insert into clt_uso values(@idTelefonia, @tipoUso, @constoTran, @NumTel)
set @countUso2 = (select count(*) from clt_uso);
if (@countUso<@countUso2)
begin
set @idUso = (select MAX(id) from clt_uso);
set @costoTranFinal = @constoTran * @tiempo;
set @impuestos = (select impuestos from clt_proveedor_telefonia where id = @idTelefonia);
set @impuestosFinal = @impuestos * @costoTranFinal;
set @finalCosto = @impuestosFinal+@costoTranFinal;
insert into tbl_movimiento_telefonia values(@NumTel, @finalCosto, @idUso, GETDATE(), @tiempo)
set @countMove2 = (select count(*) from tbl_movimiento_telefonia);
if(@countMove<@countMove2)
begin
set @montoActual = (select saldo from tbl_telefonia);
set @montoNuevo = @montoActual-@finalCosto;
set @idUser = (select id from tbl_persona where telefono = @NumTel);
update tbl_telefonia set saldo = @montoNuevo where id = @NumTel;
set @montoTotal2 = (select saldo from tbl_telefonia);
if(@montoTotal>=@montoTotal2)
begin
commit transaction
select 1 result;
end
else
begin
rollback transaction
select 0 as result;
end
end
else
begin
rollback transaction
select 0 as result;
end
end
else
begin
rollback transaction 
select 0 as result;
end
end
else
begin
rollback transaction 
select 0 as result;
end
end

create procedure SP_Alta_Usu(@usuario varchar(20), @psw varchar(255), @nombre varchar(30), @a_paterno varchar(40), @a_materno varchar(40), @telefono varchar(15))
as
declare @dataUsu int;
declare @dataUsu2 int;
declare @dataPerson int;
declare @dataPerson2 int;
declare @idPerson smallint;
begin transaction
set @dataUsu = (select count(*) from tbl_usuario);
set @dataPerson = (select count(*) from tbl_persona);
insert into tbl_persona values(@nombre, @a_paterno, @a_materno, @telefono);
set @dataPerson2 = (select count(*) from tbl_persona);
if(@dataPerson<@dataPerson2)
begin
set @idPerson = (select max(id) from tbl_persona);
insert into tbl_usuario(usuario, psw, id_persona)values(@usuario, HashBytes('SHA1', @psw), @idPerson);
set @dataUsu2 = (select count(*) from tbl_usuario);
if(@dataUsu<@dataUsu2)
begin
commit transaction;
select 1 as result;
end
else
begin
rollback transaction;
select 0 as result;
end
end
else
begin
rollback transaction
select 0 as result;
end

create procedure SP_Alta_Cliente(@nombre varchar(30), @a_paterno varchar(40), @a_materno varchar(40), @telefono varchar(15), @idProveedor tinyint)
as
declare @dataUsu int;
declare @dataUsu2 int;
declare @dataPerson int;
declare @dataPerson2 int;
declare @idPerson smallint;
begin transaction
set @dataUsu = (select count(*) from tbl_telefonia);
set @dataPerson = (select count(*) from tbl_persona);
insert into tbl_persona values(@nombre, @a_paterno, @a_materno, @telefono);
set @dataPerson2 = (select count(*) from tbl_persona);
if(@dataPerson<@dataPerson2)
begin
set @idPerson = (select max(id) from tbl_persona);
insert into tbl_telefonia values(CAST(@telefono as bigint), @idProveedor, 0, @idPerson);
set @dataUsu2 = (select count(*) from tbl_telefonia);
if(@dataUsu<@dataUsu2)
begin
commit transaction;
select 1 as result;
end
else
begin
rollback transaction;
select 0 as result;
end
end
else
begin
rollback transaction
select 0 as result;
end

create procedure SP_Alta_Refrendo(@nombre varchar(30), @a_paterno varchar(40), @a_materno varchar(40), @telefono varchar(15), @placas smallint)
as
declare @dataUsu int;
declare @dataUsu2 int;
declare @dataPerson int;
declare @dataPerson2 int;
declare @idPerson smallint;
begin transaction
set @dataUsu = (select count(*) from tbl_refrendo);
set @dataPerson = (select count(*) from tbl_persona);
insert into tbl_persona values(@nombre, @a_paterno, @a_materno, @telefono);
set @dataPerson2 = (select count(*) from tbl_persona);
if(@dataPerson<@dataPerson2)
begin
set @idPerson = (select max(id) from tbl_persona);
insert into tbl_refrendo values(@placas, 0, @idPerson, GETDATE());
set @dataUsu2 = (select count(*) from tbl_refrendo);
if(@dataUsu<@dataUsu2)
begin
commit transaction;
select 1 as result;
end
else
begin
rollback transaction;
select 0 as result;
end
end
else
begin
rollback transaction
select 0 as result;
end

create procedure SP_Pago_Refrendo(@placas smallint, @pago float)
as
declare @refrendo float;
declare @refrendoNuevo float;
declare @refrendoFinal float;
begin transaction;
set @refrendo = (select saldo from tbl_refrendo);
set @refrendoNuevo = @refrendo - @pago;
update tbl_refrendo set saldo = @refrendoNuevo where id = @placas;
set @refrendoFinal = (select saldo from tbl_refrendo);
if(@refrendo<>@refrendoFinal)
begin
commit transaction;
select 1 as result;
end
else
begin
rollback transaction
select 0 as result;
end
end

create procedure SP_Abono_Refrendo(@placas smallint, @pago float)
as
declare @refrendo float;
declare @refrendoNuevo float;
declare @refrendoFinal float;
begin transaction;
set @refrendo = (select saldo from tbl_refrendo);
set @refrendoNuevo = @pago + @refrendo;
update tbl_refrendo set saldo = @refrendoNuevo where id = @placas;
set @refrendoFinal = (select saldo from tbl_refrendo);
if(@refrendo<>@refrendoFinal)
begin
commit transaction;
select 1 as result;
end
else
begin
rollback transaction
select 0 as result;
end
end

exec SP_Abono_Refrendo 7568, 1000000

select * from tbl_refrendo

exec SP_Alta_Cliente 'Pedro', 'Pérez', 'Jiménez', '4771598523', 1

exec SP_Uso_Tel 2, 1, 2.5, 4771112781

select * from tbl_movimiento_telefonia

drop procedure SP_Abono_Refrendo

exec SP_Alta_Saldo 4771112781, 1, 10.0

insert into clt_uso values(1, 1, 4771112781, 0)

select * from clt_tipo_movimiento

insert into tbl_persona values('Rogelio', 'Andrade', 'Flores', '4771112781')

insert into tbl_telefonia values(4771112781, 1, 0, 1)

select * from tbl_telefonia


select * from clt_tipo_movimiento

update clt_tipo_movimiento set costo = 0;

select * from clt_uso

delete clt_uso


delete tbl_movimiento_telefonia

ALTER TABLE clt_tipo_movimiento
ADD costo float;

delete tbl_telefonia

select * from clt_tipo_movimiento

select * from clt_proveedor_telefonia

--insert into clt_proveedor_telefonia values('ATT', 'Companía ATT', 0.16);
--insert into clt_proveedor_telefonia values('Telcel', 'Companía Telcel', 0.12);
--insert into clt_proveedor_telefonia values('Movistar', 'Companía Movistar', 0.10);
--insert into clt_proveedor_telefonia values('Virgin', 'Companía Virgin', 0.17);


--insert into clt_tipo_movimiento values('Llamada', 'Se ha hecho una llamada', 1)