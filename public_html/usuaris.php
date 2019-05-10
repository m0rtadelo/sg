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
        tr:hover{color: #000000; background-color: #00ffff;}
        a {text-decoration: none; color: #000000;}
        body {font-family: verdana; font-size: 8pt; margin: 0px;}
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
            font-size: 9pt;
            color: #000000;
            padding-left: 10px;
            padding-right: 10px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-spacing: 0px;
        }
        .close{
            position: absolute;
            right: 10px;
            top: 10px;
            border: 0px;   
            }                 
    </style>
</head>
<body>
    <p class="title">Llista d'usuaris</p>
<a href="javascript:parent.document.getElementById('us').style.display='none';">
<img src="img/close.gif" id="close" name="close" class="close" alt="Tancar" title="Tancar">
</a>

<table id="usuaris" name="usuaris">
    <tr>
        <td class="desc">Nom</td>
        <td class="desc">E-mail</td>
        <td class="desc">Perfil</td>
        <td class="desc">Acció</td>
        <td>
            &nbsp<a href="javascript:getUsers();"><img src="img/refresh.gif" alt="Recarregar dades" title="Recarregar dades"></a>
            &nbsp<a href="edituser.php?a=add"><img src="img/add.gif" alt="Afegir nou usuari" title="Afegir nou usuari"></a>
        </td>
    </tr>
</table>	
</body>
<script language="javascript">

/**
    change password
*/
function rePass(idusuari){

    // confirmation dialog
    if(confirm("Aquesta acció modificarà la contrasenya per a l'usuari indicat.\nVoleu continuar?")){

        // Repass user Async
        $.getJSON("logic/usuaris.php?a=repass&id=" + idusuari, function(json){

            // Checking result
            if(json.status == "success"){
                alert("S'ha enviat un correu electrònic a l'usuari indicat amb les instruccions per a re-generar una nova contrasenya.");
            }else{
                alert("No ha estat possible re-generar la contrasenya!");
            }
        });
    }
}

/**
    delete selected user
*/
function deleteUser(idusuari){

    // confirmation dialog
    if(confirm("Aquesta acció esborrarà al usuari indicat i totes les seves dades.\nVoleu continuar?")){

        // Delete user Async call
        $.getJSON("logic/usuaris.php?a=del&id=" + idusuari, function(json){

            // Checking result
            if(json.status == "success"){
                alert("S'ha esborrat a l'usuari indicat i les seves dades relacionades.");
            }else{

                // unable to delete user (Error?)
                alert("No ha estat possible esborrar a l'usuari indicat!");

            }

            // reloading users
            getUsers();
        });

    }
}

/**
    reload actual users into table
*/
function getUsers(){
// Removing data from table
$('#usuaris tr').not(':first').remove();
var html='';
// Loading new data into table
$.getJSON("logic/getUsers.php",function(json){

            // status?
            if (json.status == "success"){
                var cl="#FFFFFF";
                $.each(json.members,function(i,dat){

                    // switching bg color
                    if(cl!="#FFFFFF")
                        cl="#FFFFFF";
                    else
                        cl="#EEEEEE";

                    // edit link
                    h_edit = '<a href="edituser.php?a=edit&id='+dat.idusuari+'"">';
                    
                    // delete link
                    h_delete = '<a href="javascript:deleteUser('+dat.idusuari+')">';
                    
                    // repass link
                    h_pass = '<a href="javascript:rePass('+dat.idusuari+')">';

                    // adding table row
                    html += 
                    '<tr bgcolor="' + cl + '" onclick=""><td>' + h_edit + dat.nom + '</a>' +
                    '</td><td>' + h_edit + dat.usuari + '</a>' +
                    '</td><td>' + h_edit + dat.perfil + '</a>' +
                    '</td><td colspan=2>' + 
                    '&nbsp'+h_edit+'<img src="img/edit.gif" alt="Editar usuari" title="Editar usuari"></a>' +
                    '&nbsp'+h_pass+'<img src="img/pass.gif" alt="Canviar contrasenya" title="Canviar contrasenya"></a>' +
                    '&nbsp'+h_delete+'<img src="img/delete.gif" alt="Esborrar usuari" title="Esborrar usuari"></a>' +
                    '</td></tr>';
                });     
                $('#usuaris tr').first().after(html);        
            }else{
                alert(MSG_UNTRUSTED);
                common.closeSession();
            }
        });     
}
getUsers();
</script>
</html>

<?php
}
?>