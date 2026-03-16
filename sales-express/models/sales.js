const mongoose = require('mongoose');

const SaleSchema = new mongoose.Schema({
    userId: { type: String, required: true },    // ID del usuario que viene del JWT
    productId: { type: String, required: true }, // ID del producto de Firebase
    productName: { type: String, required: true },
    quantity: { type: Number, required: true },
    totalPrice: { type: Number, required: true },
    date: { type: Date, default: Date.now }      // Fecha automática
});

module.exports = mongoose.model('Sale', SaleSchema);