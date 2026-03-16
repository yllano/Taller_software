from flask import Flask, request, jsonify
from flask_cors import CORS
from models import Product

app = Flask(__name__)
CORS(app)

@app.route('/products', methods=['POST'])
def add_product():
    data = request.json
    # Validación básica
    if not all(k in data for k in ('name', 'price', 'stock')):
        return jsonify({"error": "Faltan datos obligatorios"}), 400
    
    product_id = Product.create(data)
    return jsonify({"message": "Producto creado", "id": product_id}), 201

@app.route('/products/<id>', methods=['GET'])
def get_product(id):
    product = Product.get_by_id(id)
    if product:
        return jsonify(product), 200
    return jsonify({"error": "No encontrado"}), 404

@app.route('/products/<id>/stock', methods=['PATCH'])
def change_stock(id):
    data = request.json
    quantity = data.get('quantity', 0)
    
    result = Product.update_stock(id, quantity)
    return jsonify(result), result.get('status', 200)

if __name__ == '__main__':
    app.run(port=5000, debug=True)