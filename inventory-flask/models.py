from config import db

class Product:
    collection = db.collection('products')

    @staticmethod
    def create(data):
        """Crea un nuevo producto"""
        # Estructura: name, price, stock, description
        doc_ref = Product.collection.add(data)
        return doc_ref[1].id # Retorna el ID generado

    @staticmethod
    def get_by_id(product_id):
        """Busca un producto por su ID"""
        doc = Product.collection.document(product_id).get()
        if doc.exists:
            data = doc.to_dict()
            data['id'] = doc.id
            return data
        return None

    @staticmethod
    def update_stock(product_id, quantity_to_reduce):
        """Verifica y descuenta el stock"""
        doc_ref = Product.collection.document(product_id)
        doc = doc_ref.get()

        if not doc.exists:
            return {"error": "Producto no encontrado", "status": 404}

        current_stock = doc.to_dict().get('stock', 0)

        if current_stock < quantity_to_reduce:
            return {"error": "Stock insuficiente", "status": 400}

        new_stock = current_stock - quantity_to_reduce
        doc_ref.update({'stock': new_stock})
        return {"message": "Stock actualizado", "new_stock": new_stock, "status": 200}