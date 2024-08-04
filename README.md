# API RANKINGHUB #
## Endpoints, breve descripción y ejemplos ##

## Introducción
En la siguiente documentación podremos ver distintos endpoints con distintas funciones, y algunos con parámetros disponibles.

# localhost/RankingHub3/api/juegos 
Este endpoint obtiene todos los juegos de la base de datos, ordenados por defecto (por id,  en orden ascendente).
```
{
        "id": 1,
        "nombre": "Minecraft",
        "categoria": "Battle Royale",
        "precio": 29.99,
        "fecha": "2011-11-18"
    },
    {
        "id": 2,
        "nombre": "Grand Theft Auto V",
        "categoria": "Aventura",
        "precio": 29.99,
        "fecha": "2013-09-17"
    },
    {
        "id": 3,
        "nombre": "Tetris",
        "categoria": "Puzzle",
        "precio": 4.99,
        "fecha": "1984-06-06"
    } ...
```

# Clasificar y ordenar #
Este endpoint también puede usarse con query params, que nos permite realizar distintas funciones, por ejemplo ordenar los juegos por atributo.  
### localhost/RankingHub3/api/juegos?sort=nombre ###
```
[
    {
        "id": 11,
        "nombre": "Grand Theft Auto V",
        "categoria": "Aventura",
        "precio": 29.99,
        "fecha": "2013-09-17"
    },
    {
        "id": 1,
        "nombre": "Minecraft",
        "categoria": "Battle Royale",
        "precio": 29.99,
        "fecha": "2011-11-18"
    },
    {
        "id": 6,
        "nombre": "Super Mario Bros",
        "categoria": "Aventura",
        "precio": 9.99,
        "fecha": "1985-09-13"
    },
     ...
```
Acá podemos ver que trae los mismos objetos pero ordenados por nombre, por ejemplo.    
También podemos especificar el orden en el que se van a ver.
Por defecto los vemos en orden ascendente:
```
{
        "id": 5,
        "nombre": "PUBG",
        "categoria": "Battle royale",
        "precio": 29.99,
        "fecha": "2017-12-20"
    },
    {
        "id": 6,
        "nombre": "Super Mario Bros",
        "categoria": "Aventura",
        "precio": 9.99,
        "fecha": "1985-09-13"
    },
    {
        "id": 7,
        "nombre": "Mario Kart 8 Deluxe",
        "categoria": "Carreras",
        "precio": 59.99,
        "fecha": "2017-04-28"
    }
```

Podemos cambiarle el sentido para visualizar los juegos de manera descendente.
###   localhost/RankingHub3/api/juegos?order=desc ###
```
 {
        "id": 10,
        "nombre": "Rocket League",
        "categoria": "Deportes",
        "precio": 29.99,
        "fecha": "1996-02-27"
    },
    {
        "id": 9,
        "nombre": "Pokemon Primera Generacion",
        "categoria": "Rol",
        "precio": 29.99,
        "fecha": "1996-02-27"
    },
    {
        "id": 8,
        "nombre": "Red Dead Redemption",
        "categoria": "Acción",
        "precio": 59.99,
        "fecha": "2018-10-26"
    }
```
Estos dos parámetros pueden utilizarse al mismo tiempo, de la siguiente forma:

###   localhost/RankingHub3/api/juegos?sort=categoria&order=asc ###  
```
{
        "id": 8,
        "nombre": "Red Dead Redemption",
        "categoria": "Acción",
        "precio": 59.99,
        "fecha": "2018-10-26"
    },
    {
        "id": 6,
        "nombre": "Super Mario Bros",
        "categoria": "Aventura",
        "precio": 9.99,
        "fecha": "1985-09-13"
    },
    {
        "id": 2,
        "nombre": "Grand Theft Auto V",
        "categoria": "Aventura",
        "precio": 29.99,
        "fecha": "2013-09-17"
    }
```
En caso de que no se pasen los parametros correctamente o haya algún otro error, podrían aparecer los siguientes mensajes de error:  
```
{
    "msg:": "No se puede clasificar por este campo, seleccione uno de estos: id, nombre, categoria, precio, fecha"
}  
{
    "msg:": "El orden debe ser `asc` (ascendente) o `desc` (descendente)"
}
```
Aseguresé de indicar los parámetros correctamente, con los valores que indican los mensajes de error.  

# Paginar
El mismo endpoint (localhost/RankingHub3/juegos) puede recibir más parámetros:
### localhost/RankingHub3/api/juegos?page=2&limit=3 
```
[
    {
        "id": 4,
        "nombre": "Wii Sports",
        "categoria": "Deportes",
        "precio": 19.99,
        "fecha": "2006-11-19"
    },
    {
        "id": 5,
        "nombre": "PUBG",
        "categoria": "Battle royale",
        "precio": 29.99,
        "fecha": "2017-12-20"
    },
    {
        "id": 6,
        "nombre": "Super Mario Bros",
        "categoria": "Aventura",
        "precio": 9.99,
        "fecha": "1985-09-13"
    }
]
```
¿Qué hace esto?  
Cuando le pasamos el parametro ?page=... estamos indicando el número de página que queremos obtener, es decir, ahora nuestro juegos los podemos dividir en páginas
y podemos indicar en cuantas.  
Pero eso no es todo, porque para aprovechar al máximo esta funcionalidad, también se puede indicar el tamaño de nuestra página, usando el parámetro ?limit=...  
Esto nos indica cuantos objetos queremos traer en nuestras páginas, y en caso de no indicar un límite, por defecto sera de *5*, y además, si indicamos un límite pero 
no indicamos la página, por defecto siempre se verá la primera.      
En caso de algún error, podría ver este mensaje: 
```
{
    "msg:": "La página o el límite no es válido, por favor indique una página válida y un límite mayor a 2"
}
```
La página y el límite siempre debe ser un número entero.  

# FILTRAR
Otros parámetros que puede recibir el endpoint "localhost/RankingHub3/juegos" son los de filtración por clave y valor
### localhost/RankingHub3/api/juegos?key=categoria&value=Aventura ###
 ⚠*Siempre deben indicarse los dos*
```
[
    {
        "id": 2,
        "nombre": "Grand Theft Auto V",
        "categoria": "Aventura",
        "precio": 29.99,
        "fecha": "2013-09-17"
    },
    {
        "id": 6,
        "nombre": "Super Mario Bros",
        "categoria": "Aventura",
        "precio": 9.99,
        "fecha": "1985-09-13"
    }
]
```
Lo que hace esta función es filtrar por la clave que le pasemos, en este ejemplo fue la de categoría, donde le pasamos el campo por el que queremos filtrar
al parámetro 'key', y luego le pasamos el valor que queremos obtener por ese campo, en este caso fue "Aventura", que se lo asignamos al parámetro 'value'.  
En este caso cabe recalcar que siempre hay que indicar el par clave:valor, no es como en los parámetros anteriores que podíamos usar ambos por separado.
En caso de no seguir los pasos correctamente, podría ver el siguiente mensaje:
```
{
    "msg:": "Debe indicar si o si una clave y luego un valor, intente de nuevo"
}
```
Aseguresé que la clave exista (id, nombre, categoria, precio, fecha) y que el valor de la clave también sea válido.

## ⚠IMPORTANTE ##
No puede realizar varias acciones a la vez, por ejemplo, si está clasificando y ordenando, no puede paginar ni filtrar. O en caso de estar filtrando, no puede filtrar o clasificar.  
Los pares de query params son 3 y deben usarse estrictamente así:  

**sort || order (Puede indicarse uno u otro, o ambos)  
page || limit (Puede indicarse uno u otro, o ambos)  
key && value (Deben indicarse los dos estrictamente)**


## localhost/Web2/RankingHub3/juegos/id ##
Este endpoint se encarga de obtener un juego por id, indicado por el número de id que se encuentre en el parámetro luego de juegos/
Por ejemplo: localhost/RankingHub3/api/juegos/3
`
{
    "id": 3,
    "nombre": "Tetris",
    "categoria": "Puzzle",
    "precio": 4.99,
    "fecha": "1984-06-06"
}
`
Se obtiene el juego con id '3' ya que es lo indicado en la ruta.  
En caso de que no exista un juego con ese id, se indicará el siguiente error: 
`
{
    "msg:": "El juego con el id = 33 no existe"
}
`
# Metodos ABM 
## Metodo POST  
#### localhost/RankingHub3/api/juegos
Este **endpoint** sirve para crear un nuevo juego usando el metodo POST, para poder agregarlo hay que enviar los datos que solicita la base de datos.
## Por ejemplo: 
Codigo en postman para enviar el nuevo juego:  
```
      {     //No enviamos el id porque esta en autoincrement
            "nombre": "GOD OF WAR",
            "categoria": "Accion",
            "precio": 4.99,
            "fecha": "2005-05-07"
      }
```
Una vez enviado nos tira el siguiente mensaje:
```
      {
            "msg": "El juego fue creado con el id: 11"
      }
```
Esto lo que hace es crear el juego con la id siguiente a la id del ultimo elemento de la tabla, Por ejemplo:
```
    {
        "id": 10,
        "nombre": "Rocket League",
        "categoria": "Deportes",
        "precio": 29.99,
        "fecha": "1996-02-27"
      },
    {
        "id": 11,
        "nombre": "GOD OF WAR",
        "categoria": "Accion",
        "precio": 4.99,
        "fecha": "2005-05-07"
      }
```
En caso de que haya un error con el ingreso de datos, o que los datos no coincidan con los datos implementados en la tabla, nos dara el siguiente mensaje:
```
404 BAD REQUEST
{
    "msg": "Los datos igresados no coinciden con los datos solicitados para agregar el juego"
}
```
## Metodo PUT
#### localhost/RankingHub3/juegos/:ID
El **endpoint** para modificar un juego usando el metodo PUT es localhost/RankingHub3/api/juegos/:ID, para modificar un juego de la tabla necesitamos ingresar los datos correspondientes desde el POSTMAN.
**Por ejemlpo:**
Obtenemos el juego por id desde base de datos con el endpoint -> localhost/RankingHub3/api/juegos/10:
```
    {
        "id": 10,
        "nombre": "Rocket League",
        "categoria": "Deportes",
        "precio": 29.99,
        "fecha": "1996-02-27"
    }
```
En Postman editamos los campos a elección:
```
    {
         "id":"10",    
         "nombre": "Mario Kart",
         "categoria": "Carreras",
         "precio": 70.0,
         "fecha": "2010-09-13"
   }
```
Si la modificación fue exitosa, recibiremos el siguiente mensaje: 
```
{
    "msg:": "El juego con el id: 10 fue modificado"
}
```
El resultado de la modificación:
```
    {
        "id": 9,
        "nombre": "Pokemon Primera Generacion",
        "categoria": "Rol",
        "precio": 29.99,
        "fecha": "1996-02-27"
    },
    {
        "id": 10,
        "nombre": "Mario Kart",
        "categoria": "Carreras",
        "precio": 70,
        "fecha": "2010-09-13"
    }
```
En caso de que haya un error en la modificación, como que el id ingresado no exista en la tabla o los datos no coincidan con los datos implementados en la tabla, nos mostrará algunos de estos errores:
```
{
    "msg:": "El juego con el id: 10 no existe"
}
{
    "msg:": "Faltan datos obligatorios para modificar o los datos ingresados no coinciden con los datos de la tabla"
}
```
## Metodo DELETE
#### localhost/RankingHub3/juegos/:ID
En la baja de un juego se usa el mismo **endpoint** que en el metodo modificar, ya que eliminamos el juego en base a su id.
**Por ejemplo**
Tenemos la siguiente tabla:
```
    {
        "id": 8,
        "nombre": "Red Dead Redemption",
        "categoria": "Acción",
        "precio": 59.99,
        "fecha": "2018-10-26"
    },
    {
        "id": 9,
        "nombre": "Pokemon Primera Generacion",
        "categoria": "Rol",
        "precio": 29.99,
        "fecha": "1996-02-27"
    },
    {
        "id": 10,
        "nombre": "Mario Kart",
        "categoria": "Carreras",
        "precio": 70,
        "fecha": "2010-09-13"
    }
```
Con el **endpoint** -> localhost/RankingHub3/juegos/10 tomamos el juego con el id 10 y lo eliminamos.
Si se completó la eliminación del juego con éxito, nos mostrará el siguiente mensaje:
```
{
    "msg:": "El juego con id: 10 ha sido borrado con exito"
}
```
Ahora la tabla queda de la siguiente manera:
```
    {
        "id": 8,
        "nombre": "Red Dead Redemption",
        "categoria": "Acción",
        "precio": 59.99,
        "fecha": "2018-10-26"
    },
    {
        "id": 9,
        "nombre": "Pokemon Primera Generacion",
        "categoria": "Rol",
        "precio": 29.99,
        "fecha": "1996-02-27"
    }
```
En caso de que haya un error en el proceso de baja, como que el id no exista, nos mostrará el siguiente mensaje:
```
{
    "msg:": "El juego con id: 10 NO existe"
}
```
El id 10 ya ha sido borrado anteriormente, por eso es que nos muestra que no existe.
## VALIDACION POR TOKEN
Esta validacion se toma en postman desde el apartado de Authorization en el cual se elige el tipo que vamos a utilizar para autorizarnos. La validacion solo la hacemos en los metodos POST y PUT los cuales necesitan de un Token para poder modificar o actualizar. El token lo hicimos mediante JWT y es el siguiente:
eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c3VhcmlvIjoid2ViYWRtaW4iLCJjb250cmFzZcOxYSI6ImFkbWluIn0.DVONr3o6GnFXuCT2RDKbGQsQBMhC6s5lB_7o3FBmw3U
Este token representa al usuario (webadmin) y a la contraseña (admin).


