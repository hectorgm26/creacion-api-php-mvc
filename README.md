
# 📦 Webservice de Categorías - PHP API REST

Este proyecto es un **webservice RESTful en PHP** que permite realizar operaciones CRUD (Crear, Leer, Actualizar, Eliminar) sobre una tabla de categorías (`tm_categoria`). Es ideal para integrarse con clientes como **Postman**, **aplicaciones web o móviles**, o cualquier sistema que consuma APIs REST.

## 🧰 Tecnologías utilizadas

- PHP 8.3
- MySQL 8.0
- phpMyAdmin 5.2.2
- Servidor local (XAMPP, WAMP, Laragon, etc.)
- Postman para pruebas
- Autenticación básica con token tipo Bearer

---

## 📁 Estructura del proyecto

```
andercode_webservice/
│
├── Config/
│   └── Conexion.php         # Clase base para conexión a la base de datos
│
├── Models/
│   └── Categoria.php        # Clase con métodos CRUD para tm_categoria
│
├── Controllers/
│   └── CategoriaController.php  # Controlador principal que maneja las solicitudes y las respuestas
│
├── parametros.php           # Parámetros de conexión a la base de datos
├── andercode_webservice.sql # Script SQL con estructura y datos de ejemplo
└── README.md                # Este archivo
```

---

## 🧪 Endpoints disponibles

| Método | Endpoint               | Descripción                           | Tipo de datos     |
|--------|------------------------|---------------------------------------|-------------------|
| POST   | `?op=GetAll`           | Obtener todas las categorías activas  | JSON              |
| POST   | `?op=GetId`            | Obtener categoría por `cat_id`        | JSON (`cat_id`)   |
| POST   | `?op=Insert`           | Insertar nueva categoría              | JSON (`cat_nom`, `cat_obs`) |
| POST   | `?op=Update`           | Actualizar categoría existente        | JSON (`cat_id`, `cat_nom`, `cat_obs`) |
| POST   | `?op=Delete`           | Eliminar (desactivar) categoría       | JSON (`cat_id`)   |

> ⚠️ Todos los endpoints requieren un **token Bearer** en el header:  
> `Authorization: Bearer UwU69`

---

## 🛠️ Instalación y ejecución

1. Clona este repositorio:
   ```bash
   git clone https://github.com/tu-usuario/andercode_webservice.git
   ```

2. Crea la base de datos:
   - Abre phpMyAdmin.
   - Importa el archivo `andercode_webservice.sql`.

3. Configura tu entorno local:
   - Asegúrate de tener PHP y MySQL corriendo.
   - Coloca el proyecto en el directorio de tu servidor local (ej. `htdocs/` en XAMPP).

4. Prueba los endpoints en Postman:
   - Agrega en los **headers**:
     ```
     Authorization: Bearer UwU69
     Content-Type: application/json
     ```
   - Usa el método `POST` y agrega el parámetro `op` en la URL según la operación.

---

## 🧪 Ejemplo de body en Postman

### Insertar nueva categoría

```json
POST http://localhost/andercode_webservice/Controllers/CategoriaController.php?op=Insert

Body (raw, JSON):
{
  "cat_nom": "Microondas",
  "cat_obs": "Electrodoméstico de cocina"
}
```

---

## 📌 Notas

- Las categorías eliminadas no se borran de la base de datos, solo se marcan con estado `est = 0`.
- El proyecto está pensado para propósitos educativos o como base para proyectos más grandes.
- La autenticación es simple, con token estático. Se puede extender fácilmente con JWT u otros métodos más robustos.

---

## 🤝 Autor

Desarrollado por mi, Héctor González  

---

## 📝 Licencia

Este proyecto está bajo la licencia MIT. Consulta el archivo `LICENSE` para más detalles.
