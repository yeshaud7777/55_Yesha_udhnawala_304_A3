const express = require('express');
const app = express();
const PORT = 3000;
app.use(express.json());

const students = [
  { id: 1, name: 'Asha Patel', email: 'asha@example.com' },
  { id: 2, name: 'Rahul Shah', email: 'rahul@example.com' }
];

app.get('/', (req, res) => res.send('Express REST API is running.'));
app.get('/api/students', (req, res) => res.json(students));
app.get('/api/students/:id', (req, res) => {
  const student = students.find(s => s.id === Number(req.params.id));
  if (!student) return res.status(404).json({ error: 'Student not found' });
  res.json(student);
});
app.post('/api/students', (req, res) => {
  const { name, email } = req.body;
  if (!name || !email) return res.status(400).json({ error: 'name and email required' });
  const student = { id: students.length + 1, name, email };
  students.push(student);
  res.status(201).json(student);
});
app.listen(PORT, () => console.log(`Express API listening at http://localhost:${PORT}`));
