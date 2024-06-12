# GAMEGUESSER

¡Bienvenido a GAMEGUESSER! Este proyecto es una aplicación web diseñada para poner a prueba tu conocimiento sobre videojuegos, adivinando los juegos a partir de imágenes in-game. La aplicación está desarrollada utilizando Angular para el frontend y PHP para el backend, con una base de datos MySQL.

## Tabla de Contenidos

- [GAMEGUESSER](#gameguesser)
  - [Tabla de Contenidos](#tabla-de-contenidos)
  - [Introducción](#introducción)
  - [Características](#características)
  - [Tecnologías Utilizadas](#tecnologías-utilizadas)
  - [Instrucciones de Configuración](#instrucciones-de-configuración)
  - [Ejecución de la Aplicación](#ejecución-de-la-aplicación)
  - [Contribuciones](#contribuciones)
  - [Licencia](#licencia)

## Introducción

GAMEGUESSER fue creado para desafiar y entretener a los entusiastas de los videojuegos. El proyecto tiene como objetivo proporcionar una plataforma donde los usuarios puedan poner a prueba su conocimiento sobre videojuegos, competir en una tabla de clasificación y aprender más sobre sus juegos favoritos.

## Características

- Sistema de registro e inicio de sesión de usuarios
- Panel de administración para gestionar juegos y usuarios
- Juego de adivinanza con imágenes in-game
- Tabla de clasificación para seguir las puntuaciones de los usuarios
- Interfaz de usuario intuitiva y responsive

## Tecnologías Utilizadas

- **Frontend**: Angular, TypeScript, Bootstrap, CSS
- **Backend**: PHP
- **Base de datos**: MySQL
- **Otros**: XAMPP (para servidor local), phpMyAdmin (para gestión de la base de datos)

## Instrucciones de Configuración

Para configurar el proyecto en tu máquina local, sigue estos pasos:

1. **Instalar XAMPP**:
   Descarga e instala XAMPP desde [aquí](https://www.apachefriends.org/index.html).

2. **Configurar la Base de Datos**:
   - Abre XAMPP y inicia Apache y MySQL.
   - Abre phpMyAdmin visitando `http://localhost/phpmyadmin/` en tu navegador.
   - Crea una nueva base de datos llamada `gameguesser`.
   - Establece el nombre de usuario como `root` y la contraseña como una cadena vacía (`""`).
   - Importa el archivo `gameguesser.sql` en la base de datos `gameguesser`.

3. **Configurar el Backend**:
   - Copia la carpeta `phpgameguesser` desde el repositorio a `C:\xampp\htdocs`.

4. **Actualizar la URL del Servicio**:
   - Abre `servicio.service.ts` en tu proyecto de Angular.
   - Actualiza la variable `url` para que coincida con tu configuración. Si colocaste la carpeta `phpgameguesser` en `C:\xampp\htdocs`, la URL debe ser:
     ```typescript
     url: string = 'http://localhost/phpgameguesser/';
     ```

5. **Instalar Módulos de Node**:
   - Navega al directorio raíz de tu proyecto de Angular en el terminal.
   - Ejecuta el siguiente comando para instalar los módulos necesarios de Node:
     ```bash
     npm install
     ```

## Ejecución de la Aplicación

1. **Iniciar XAMPP**:
   - Abre XAMPP e inicia Apache y MySQL.

2. **Ejecutar la Aplicación de Angular**:
   - Abre un terminal y navega al directorio raíz de tu proyecto de Angular.
   - Ejecuta el siguiente comando para iniciar el servidor de desarrollo de Angular:
     ```bash
     ng serve
     ```
   - Abre tu navegador y visita `http://localhost:4200` para ver la aplicación en acción.

## Contribuciones

Si deseas contribuir al proyecto, por favor, haz un fork del repositorio y envía una pull request. ¡Las contribuciones son bienvenidas!

## Licencia

Este proyecto está licenciado bajo la Licencia MIT.

---

¡Esperamos que disfrutes usando GAMEGUESSER! Si tienes alguna pregunta o comentario, no dudes en ponerte en contacto. ¡Feliz juego!