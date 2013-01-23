# README #

Sample Silex website made for my talk at the
[Bilbostack 2013](http://bilbostack.com/) conference. The original website is 
made with Wordpress and this application tries to show in practice the 
advantages of Silex for developing micro websites.

---

Este repositorio contiene la aplicación Silex de prueba que preparé para mi 
charla en la conferencia [Bilbostack 2013](http://bilbostack.com/). Se trata de
una copia exacta del sitio web oficial, que está hecho con Wordpress.

El objetivo de esta aplicación es demostrar en la práctica que Silex es la mejor
opción para desarrollar sitios web pequeños de manera profesional y muy ágil.

**Cómo instalar el proyecto**

    $ git clone git://github.com/javiereguiluz/bilbostack.git bilbostack
    $ cd bilbostack
    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar install

Si al probar el proyecto por primera vez te aparece una página en blanco o
cualquier mensaje de error, es casi seguro que debes cambiar los permisos
de los directorios logs/ y/o cache/   El usuario con el que se ejecuta el
servidor web debe tener permisos de escritura en esos directorios.
