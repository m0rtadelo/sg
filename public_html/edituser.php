<?php

session_start(); 
if($_SESSION['auth']!="yes"){
    header('Location: login.html');
}
else{
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Gestió d'usuaris</title>
    <script type="text/javascript" src="js/jquery.js"></script> 
    <script type="text/javascript" src="js/jmap.js"></script>     
    <script type="text/javascript" src="js/util.js"></script> 
    <style type="text/css">
        form {text-align: center;}
        body {font-family: verdana; font-size: 9pt; margin: 0px;}
        .title {
            font-weight: bold; 
            color: #FFFFFF; 
            font-size: 12pt; 
            text-align: center;
            background-color: #BBBBBB;
            margin: 0px;
            padding: 10px;
        }
        input[type=text],select{
            font-size: 9pt;
            border: solid 1px #cccccc;
            background-color: #eeeeee;
            width: 95%;
        }
        input[type=button]{
            font-size: 9pt;
            border: solid 1px #cccccc;
            background-color: #eeeeee;
            padding: 5px;
            border-radius: 5px;
        }
        input[type=button]:hover{
            background-color: #cccccc;
            border: solid 1px #aaaaaa;
        }
        .desc{
            text-align: justify;
            font-size: 8pt;
            color: #777777;
            padding-left: 10px;
            padding-right: 10px;        
        }
        table {
            width: 100%;
        }
        .close{
            position: absolute;
            right: 10px;
            top: 10px;
            border: 0px;            
        }
    </style>
<script language="javascript">

function goback(){
    document.getElementById('fback').submit();
}

function getUser(id){
    $.getJSON("logic/getUserById.php?id="+id,function(json){
        // status?
        if (json.status == "success"){
            $.each(json.members,function(i,dat){
                $('#nom').val(dat.nom);
                $('#email').val(dat.usuari);
                $('#perfil').val(dat.idperfil);
            });                
        }else{
            alert("No és possible accedir a les dades!");
        }
    });
}

function getPerfils(){
    $('#perfil').find('option').remove();
    $.getJSON("logic/getPerfils.php",function(json){
        // status?
        if (json.status == "success"){
            $.each(json.members,function(i,dat){
                $('#perfil').append($('<option>').text(dat.nom).attr('value', dat.idperfil));
            });                
        }else{
            alert("No és possible accedir a les dades!");
        }
    });    
}
function load(){
    var action = $('#action').val();
    var id = $('#id').val();

    getPerfils();

    if(action=='edit'){
        getUser(id);
        $('#desc').text("Aquest formulari permet modificar les dades de l\'usuari indicat. Informi les dades que es mostren a continuació i premi el botó [Desar els canvis] per a gravar-ho.\nPremi el botó [Tornar] per a tornar a la llista d'usuaris sense gravar.");
    }else{
        $('#desc').text("Aquest formulari permet afegir un nou usuari. Informi les dades que es mostren a continuació i premi el botó [Desar els canvis] per a afegir al nou usuari.\nPremi el botó [Tornar] per a tornar a la llista d'usuaris sense gravar.");
    }

    document.getElementById('nom').focus();
}
function validate(data){
    return true;
}
function savef(){

    // getting values
    var data = [];
    data.action = $('#action').val();
    data.id = $('#id').val();
    data.nom = $('#nom').val();
    data.email = $('#email').val();
    data.perfil = $('#perfil').val();

    if(validate(data)){

        // Saving values
        $.post("logic/edituser.php", $('#edituser').serialize(), function(data){
            if(data.status == "success"){
                if( $('#action').val()=='edit')
                    alert("S'han desat correctament els canvis sol·licitats");
                if( $('#action').val()=='add')
                    alert("Usuari afegit!\nRecordi que cal re-generar la contrasenya per a enviar-li les dades d'accés.");
                goback();
            }else{
                if(data.status == "error")
                    alert(data.msg);
                else
                    alert("No ha estat possible desar els canvis!");
            }
        });
    }
}
</script>
</head>
<body onload="javascript:load();">

<form name="fback" id="fback" method="get" action="usuaris.php"></form>
<form name="edituser" id="edituser">
<input type="hidden" name="action" id="action" value="<?php echo $_GET['a'] ?>">
<input type="hidden" name="id" id="id" value="<?php echo $_GET['id'] ?>">
<p class="title">Dades de l'usuari</p>
<a href="javascript:parent.document.getElementById('us').style.display='none';">
<img src="img/close.gif" id="close" name="close" class="close" alt="Tancar" title="Tancar">
</a>
<p class="desc" id="desc" name="desc">...</p>

<table>
    <tr>
        <td align="right"><label for="nom">Nom:&nbsp;</label></td>
        <td><input type="text" name="nom" id="nom" value=""></td>
    </tr>
    <tr>
        <td align="right"><label for="email">E-mail:&nbsp;</label></td>
        <td><input type="text" name="email" id="email" value=""></td>
    </tr>
    <tr>
        <td align="right"><label for="perfil">Perfil:&nbsp;</label></td>
        <td><select name="perfil" id="perfil"></select></td>
    </tr>
</table>
<br>
<hr>
<p style="text-align: right;">
    <input type="button" value="Tornar" id="backb" name="backb" onclick="javascript:goback();"> 
    &nbsp;
    <input type="button" value="Desar els canvis" id="save" name="save" onclick="javascript:savef();" style="margin-right: 10px;">
</p>
</form>

</body>
</html>

<?php
}
?>