## Creamos nuestro archivo .env
Creamos nuestro archivo.env y copiamos lo que tenemos dentro del archivo .env.example

## Para levantar el contenedor de docker tenemos que usar el comando 
./vendor/bin/sail up -d

## Para correr las migraciones corremos el comando
./vendor/bin/sail artisan migrate

## Para probar las apis
Podemos probarlas en PostMan

### Registro de usuario
POST http://localhost/api/register
Content-Type: application/json

{
    "nombre": "Juan",
    "apellido": "Pérez",
    "email": "juan@example.com",
    "password": "123456",
    "role": "admin" || "vendedor"
}

### Login 
POST http://localhost/api/login
Content-Type: application/json

{
    "email": "juan@example.com",
    "password": "123456"
}

### Crear un producto
POST http://localhost/api/products
Content-Type: application/json
Authorization: Bearer {TOKEN}

{
    "sku": "P001",
    "nombre": "Producto de Prueba",
    "precio_unitario": 10.99,
    "stock": 50
}

### Listar todos los productos 
GET http://localhost/api/products
Authorization: Bearer {TOKEN}

### Obtener un producto por id
GET http://localhost/api/products/1
Authorization: Bearer {TOKEN}

### Actualizar un producto 
PUT http://localhost/api/products/1
Content-Type: application/json
Authorization: Bearer {TOKEN}

{
    "precio_unitario": 12.99,
    "stock": 40
}

### Eliminar un producto, esto solo funciona para un admin
DELETE http://localhost/api/products/1
Authorization: Bearer {TOKEN}

### Crear una venta
POST http://localhost/api/ventas
Authorization: Bearer {TOKEN}
{
    "cliente_nombre": "Juan Pérez",
    "cliente_identificacion_tipo": "DNI" || "RUC",
    "cliente_identificacion": "12345678",
    "cliente_email": "juan.perez@email.com",
    "productos": [
        {
            "id": 1,
            "cantidad": 2
        }
    ]
}

### Para probar el reporte
POST http://localhost/api/reporte-ventas
Authorization: Bearer {TOKEN}
{
  "start_date": "2025-01-01",
  "end_date": "2025-02-27",
  "format": "xlsx" || "json"
}
Probando en PostMan no se descarga automaticamente debe darle en save to file y se tendra el archivo correctamente