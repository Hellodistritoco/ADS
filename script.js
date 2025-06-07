document.getElementById('estrategiaForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const formData = new FormData(this);

  fetch('guardar.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.text())
  .then(data => {
    alert('Estrategia guardada correctamente');
    this.reset();
  })
  .catch(error => {
    alert('Error al guardar');
    console.error(error);
  });
});
