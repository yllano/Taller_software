require('dotenv').config();
const express = require('express');
const cors = require('cors');
const connectDB = require('./config/db');
const Sale = require('./models/sales');

const app = express();

// Conectar a BD
connectDB();

// Middleware
app.use(cors());
app.use(express.json());

// --- ENDPOINTS ---

// A. Registrar una venta
app.post('/sales', async (req, res) => {
    try {
        const { userId, productId, productName, quantity, totalPrice } = req.body;
        const newSale = new Sale({ userId, productId, productName, quantity, totalPrice });
        await newSale.save();
        res.status(201).json({ message: 'Venta registrada en MongoDB', saleId: newSale._id });
    } catch (err) {
        res.status(500).json({ error: 'Error al registrar venta', detail: err.message });
    }
});

// B. Consultar ventas por Usuario
app.get('/sales/user/:userId', async (req, res) => {
    try {
        const sales = await Sale.find({ userId: req.params.userId });
        res.json(sales);
    } catch (err) {
        res.status(500).json({ error: 'Error al consultar ventas' });
    }
});

// C. Consultar todas las ventas (Para reportes)
app.get('/sales', async (req, res) => {
    try {
        const sales = await Sale.find().sort({ date: -1 });
        res.json(sales);
    } catch (err) {
        res.status(500).json({ error: 'Error al obtener historial' });
    }
});

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => console.log(`Servidor de ventas en puerto ${PORT}`));