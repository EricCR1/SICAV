document.getElementById('visitanteForm').addEventListener('submit', async (e) => {
  e.preventDefault(); // Evita que abra el PHP en otra pestaña

  const formData = new FormData(e.target);
  const mensaje = document.getElementById('mensaje');

  try {
    const res = await fetch('guardar.php', {
      method: 'POST',
      body: formData
    });

    const texto = await res.text();
    mensaje.style.color = texto.includes("✅") ? "green" : "red";
    mensaje.textContent = texto;

    // Limpia el formulario
    if (texto.includes("✅")) e.target.reset();

  } catch (err) {
    mensaje.style.color = "red";
    mensaje.textContent = "❌ Error al enviar datos";
    console.error(err);
  }
});