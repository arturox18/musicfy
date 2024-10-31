
<link rel="stylesheet" href="diseño.css">

<div class="login-container">
  <h2>Perfil de usuario</h2>
  
  <div class="profile-photo">
    <img src="ruta-de-foto-actual.jpg" alt="Foto de perfil" class="profile-image">
    <button onclick="changePhoto()">Cambiar foto</button>
  </div>

  <div class="profile-info">
    <label for="username">Nombre</label>
    <input type="text" id="username" value="NombreActual">
  </div>

  <div class="action-buttons">
    <button onclick="saveChanges()">Guardar cambios</button>
    <button onclick="cancelChanges()">Cancelar</button>
  </div>
</div>
