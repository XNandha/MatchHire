// api/userAPI.js
const express = require('express');
const router = express.Router();
const db = require('./database');
const bcrypt = require('bcryptjs');

// Register user
router.post('/register', (req, res) => {
  const { name, email, password, role } = req.body;
  const hashed = bcrypt.hashSync(password, 10);

  db.query(
    'INSERT INTO users (name, email, password, role, created_at) VALUES (?, ?, ?, ?, NOW())',
    [name, email, hashed, role],
    (err, result) => {
      if (err) return res.status(500).send(err);
      res.send({ message: 'Registrasi berhasil!' });
    }
  );
});

// Login user
router.post('/login', (req, res) => {
  const { email, password } = req.body;

  db.query('SELECT * FROM users WHERE email = ?', [email], (err, rows) => {
    if (err || rows.length === 0) return res.status(404).send({ message: 'User tidak ditemukan' });

    const user = rows[0];
    if (bcrypt.compareSync(password, user.password)) {
      res.send({ message: 'Login sukses', user });
    } else {
      res.status(401).send({ message: 'Password salah' });
    }
  });
});

module.exports = router;
