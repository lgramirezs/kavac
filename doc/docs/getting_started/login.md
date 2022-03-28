# Acceder al sistema KAVAC  
**************************

![Screenshot](../img/logokavac.png#imagen)

Acceda al sistema desde cualquier navegador de Internet es recomendable utilizar Mozilla Firefox o Google Chrome, una vez abierto el navegador ingrese el nombre de dominio que ha sido configurado para el sistema KAVAC.

A continuación tendrá acceso a la **pantalla principal** o **página de inicio de sesión** del sistema.

![Screenshot](../img/figure_42.png#imagen)<div style="text-align: center;font-weight: bold"> Figura: Inicio de Sesión</div>

!!! note "Nota"

    Si la instancia del sistema KAVAC se encuentra en un entorno de desarrollo y ha sido ejecutada a través del comando **php artisan serve** visite la dirección 127.0.0.1:8000 o localhost:8000

## Inicio de sesión 

-   Ingresar las credenciales de la cuenta de usuario **Nombre de usuario** y **Contraseña**. 

![Screenshot](../img/user_data.png#imagen)

-   Verificar el texto de la imagen y completar el campo **Captcha**.

![Screenshot](../img/captcha_2.png#imagen)

!!! note ""
    Para cambiar la imagen de captcha presione el botón **Recargar** ubicado a la derecha de la imagen. 

    ![Screenshot](../img/captcha.png#imagen)

-   Active el botón de selección del campo **Recuérdame**, si desea almacenar los datos de la cuenta de usuario la próxima vez que inicie sesión.    

![Screenshot](../img/remember.png#imagen)

-   Presione el botón **Accesso** para iniciar sesión.

![Screenshot](../img/logout.png#imagen)


-   A continuación tendrá acceso a la pantalla prinicipal de su cuenta de usuario.

![Screenshot](../img/figure_56.png#imagen)<div style="text-align: center;font-weight: bold"> Figura: Pantalla de Inicio</div>

<br/>

!!! warning "Bloqueo de cuenta de usuario"
    
    -   Se debe tener en cuenta que luego de haber completado tres intentos fallidos de inicio de sesión, la cuenta de usuario es bloqueda. 

    ![Screenshot](../img/block.png#imagen)

!!! success "Desbloquear cuenta de usuario"

        Para desbloquear una cuenta de usuario se requiere restablecer contraseña, para recuperar contraseña se deben seguir los siguientes pasos: 

        -   Presionar el enlace ¿Olvidaste la contraseña?

        ![Screenshot](../img/password.png#imagen)

        - A continuación ingrese el **correo electrónico** de la cuenta de usuario y presione el botón **Enviar enlace**.

        ![Screenshot](../img/reset.png#imagen)

        - Consulte la bandeja de correos recibidos de su cuenta de correo electrónico y obtenga la nueva contraseña suministrada por el sistema. 

        - Retorne a la página de inicio de sesión e ingrese el **Nombre de usuario** y la **Contraseña** suministrada por el sistema. 

