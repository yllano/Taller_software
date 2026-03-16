

## . Endpoints Disponibles en el Gateway


### 3.1. Obtener Token (Login)
* **Método y Ruta**: `POST http://127.0.0.1:8000/api/login`
* **Descripción**: Valida credenciales e inyecta un JWT que expira en 1 hora.
* **Body (JSON)**:
  ```json
  {
      "email": "admin@tienda.com",
      "password": "123456"
  }
  ```

### 3.2. Realizar Registro de Venta
* **Método y Ruta**: `POST http://127.0.0.1:8000/api/ventas`
* **Headers Necesarios**: `Authorization: Bearer <TU_JWT_AQUI>`
* **Descripción**: Endpoint protegido que cobra la venta al usuario, valida el stock y reduce el inventario global desde los demás Microservicios.
* **Body (JSON)**:
  ```json
  {
      "product_id": "<ID_DE_FIREBASE>",
      "quantity": 2
  }
  ```

---

## 4. Pruebas Funcionales usando Thunder Client (VS Code)

Para demostrar que el sistema entero aprueba los requerimientos, ejecuta las pruebas en forma de cascada:

### Paso 1: Autenticación (Gateway)
1. En Thunder Client, crea un **Nuevo Request** tipo `POST` hacia `http://127.0.0.1:8000/api/login`.
2. En la pestaña **Body > JSON**, envía:
   ```json
   { "email": "admin@tienda.com", "password": "123456" }
   ```
3. Clic en **Send**. En la respuesta verás la prop `"token"`. Copia ese `string`.

### Paso 2: Consultar Stock Inicial o Crear Producto (Flask)
Si no tienes el ID de un producto con stock:
1. Nuevo `POST` a `http://127.0.0.1:5000/products`.
2. Body (JSON): `{ "name": "Laptop", "price": 1000, "stock": 50 }`
3. Clic en **Send** y te devolverá el `"id"` string de Firebase. (Apunta este dato que lo usarás).

### Paso 3: Orquestar Venta (Gateway / Flask / Express)
1. Nuevo `POST` a `http://127.0.0.1:8000/api/ventas`
2. Ve a la pestaña **Headers** de Thunder Client y agrega `Authorization` con valor `Bearer (PEGA_EL_TOKEN_DEL_PASO_1)`.
3. Ve a la pestaña **Body > JSON**:
   ```json
   {
       "product_id": "(PEGA_EL_ID_DEL_PRODUCTO_PASO_2)",
       "quantity": 3
   }
   ```
4. Clic en **Send**. Resultará en `HTTP 201 Created` y dirá `"gateway_status": "Sincronizado"`.
5. *(Se validó tú usuario, tu JWT, el microservicio vió que podías comprar y restó en la base).*

### Paso 4: Validar Persistencia Independiente
* Valida consultando el Express en `GET http://127.0.0.1:3000/sales`. Te deolverá tu historial recién guardado en **MongoDB** con tu UserID insertado del JWT.
* Valida en el GET al Flask `http://127.0.0.1:5000/products/(ID)` que el `"stock"` original de Firebase **descendió un -3**. 

---

## 5. Manual de Despliegue Automatizado

### Paso 1: Instalación de Dependencias (`setup.bat`)
La primera vez que se descargue este repositorio, haz doble clic en el archivo **`setup.bat`**. 
Este script de forma automática:
1. Instalará los `node_modules` de Express.
2. Descargará los paquetes de PHP/Composer para Laravel.
3. Creará el entorno virtual (`venv`) de Python e instalará `Flask`, `flask-cors` y `firebase-admin`.

### Paso 2: Ejecución de los Microservicios (`start.bat`)
Una vez instaladas las dependencias, haz doble clic en el archivo **`start.bat`**.
Este script abrirá simultáneamente **3 terminales negras independientes**, levantando:
* La base de datos / Motor en Express (Puerto 3000)
* El motor del Inventario en Flask (Puerto 5000)
* El API Gateway en Laravel (Puerto 8000)

*Nota: Deja estas 3 ventanas abiertas mientras realices las pruebas en Thunder Client.*
