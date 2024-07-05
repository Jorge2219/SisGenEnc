<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

   <link rel="stylesheet" href="css/bootstrap.css">
   <link rel="stylesheet" type="text/css" href="css/style.css">
   <link rel="stylesheet" href="css/estilos123.css"> 
   <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
   <!-- <link rel="stylesheet" href="css/all.min.css"> -->
   <!-- <link rel="stylesheet" href="css/fontawesome.min.css"> -->
   <link rel="stylesheet" href="css/sweetalert2.min.css">
   <script src="js/sweetalert2.all.min.js"></script>
   <title>Registro de Usuarios</title>
  
</head>

<body>
   <img class="wave" src="img/wave1.png">
   <div class="container">
   <div class="img">
      <img src="img/LogoIPN.png" style="width: 300px; height: auto;">
      </div>
      <div class="login-content">
         <form method="post" action="">
            <img src="img/upiiz.png">
            <h2 class="title">Registrar</h2>
            <?php
            include("conexion.php");
            include("modelo/controlador_registro.php");
            ?>
            <div class="input-div one">
               <div class="i">
                  <i class="fas fa-user"></i>
               </div>
               <div class="div">
                  <h5>Nombre</h5>
                  <input id="nombre" type="text" class="input" name="nombre">
               </div>
            </div>
            <div class="input-div one">
               <div class="i">
                  <i class="fas fa-certificate"></i>
               </div>
               <div class="div">
                  <h5>Apellidos</h5>
                  <input id="apellidos" type="text" class="input" name="apellidos">
               </div>
            </div>
            <div class="input-div one">
               <div class="i">
                  <i class="fas fa-user-tag"></i>
               </div>
               <div class="div">
                  <h5>Área/Departamento</h5>
                  <input id="area" type="text" class="input" name="area">
               </div>
            </div>
            <div class="input-div one">
               <div class="i">
                  <i class="fas fa-envelope"></i>
               </div>
               <div class="div">
                  <h5>Usuario@ipn.mx</h5>
                  <input id="usuario" type="email" class="input" name="usuario">
               </div>
            </div>
            <div class="input-div pass">
               <div class="i">
                  <i class="fas fa-lock"></i>
               </div>
               <div class="div">
                  <h5>Contraseña</h5>
                  <input type="password" id="input" class="input" name="password">
               </div>
            </div>
            <div class="view">
               <div class="fas fa-eye verPassword" onclick="vista()" id="verPassword"></div>
            </div>
            
            <div class="text-center">
               
               <a class="font-italic isai5" href="login.php">Regresar</a>
            </div>
            <input name="btnregistrar" class="btn" type="submit" value="Registrarse">
         </form>
      </div>
   </div>
   <script src="js/fontawesome.js"></script>
   <script src="js/main.js"></script>
   <script src="js/main2.js"></script>
   <script src="js/jquery.min.js"></script>
   <script src="js/bootstrap.js"></script>
   <script src="js/bootstrap.bundle.js"></script>

</body>

</html>
