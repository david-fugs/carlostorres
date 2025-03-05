
<style>
/* Estilos generales */
.sidebar {
  width: 260px;
    background-color: white;
    color: grey;
    padding: 15px;
    height: 100vh;
    position: fixed;
    overflow-y: auto;
    transition: transform 0.3s ease-in-out;

}

/* Enlaces principales */
.nav_link {
    display: flex;
    align-items: center;
    padding: 10px;
    color:rgb(104, 105, 105);
    text-decoration: none;
    cursor: pointer;
    transition: 0.3s;
    border-radius: 5px;
}

.nav_link:hover {
    background-color: #61a6f8;
}

/* Iconos */
.navlink_icon {
    margin-right: 10px;
}

/* Flecha para collapse */
.arrow-left {
    margin-left: auto;
    transition: transform 0.3s ease;
}

/* Submenú oculto */
.submenu {
    display: none;
    padding-left: 20px;
}

/* Mostrar submenú cuando está activo */
.item.active .submenu {
    display: block;
}

/* Rotar la flecha cuando el menú está activo */
.item.active .arrow-left {
    transform: rotate(90deg);
}
.menu_items {
    list-style: none; 
    padding: 0; 
    margin: 0; 
}
.submenu {
    list-style: none;
    padding-left: 10px; 
}
.menu_content {
    font-size: 18px;
}
@media (max-width: 768px) {
    .menu-toggle {
        display: block;
        z-index: 1100;
    }
}
.menu-toggle {
    display: none;
    font-size: 24px;
    cursor: pointer;
    position: absolute;
    top: 15px;
    left: 15px;
}
/* 📌 Ocultar sidebar en pantallas pequeñas */
@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%); /* Oculta el menú */
        width: 250px;
        position: fixed;
        z-index: 1000;
    }

    .sidebar.show {
        transform: translateX(0); /* Muestra el menú */
    }
}

</style>
<!-- Botón de menú para móviles -->
<div class="menu-toggle">
    <i class="fa-solid fa-bars"></i> <!-- Icono de menú -->
</div>
<nav class="sidebar">
    <div class="menu_content">
        <ul class="menu_items">
            <!-- Equipo -->
            <li class="item">
                <div class="nav_link submenu_item">
                    <span class="navlink_icon">
                        <i class="fa-solid fa-people-group"></i>
                    </span>
                    <span class="navlink">Equipo</span>
                    <i class="bx bx-chevron-right arrow-left"></i>
                </div>
                <ul class="menu_items submenu">
                    <li><a href="/code/mie/addmie.php" class="nav_link sublink">Agregar Referido</a></li>
                    <li><a href="/code/mie/showMembers.php" class="nav_link sublink">Consultar Referido</a></li>
                </ul>
            </li>
            <?php if($tipo_usu == 1) { ?>
            <li class="item">
                <div class="nav_link submenu_item">
                    <span class="navlink_icon">
                        <i class="fa-solid fa-people-group"></i>
                    </span>
                    <span class="navlink">Lideres</span>
                    <i class="bx bx-chevron-right arrow-left"></i>
                </div>
                <ul class="menu_items submenu">
                    <li><a href="/code/leaders/createLeaders.php" class="nav_link sublink">Crear lider</a></li>
                    <li><a href="/code/leaders/showLeaders.php" class="nav_link sublink">Consultar Lideres</a></li>
                </ul>
            </li>
            <?php } ?>
            <!-- Cumpleaños -->
            <li class="item">
                <div class="nav_link submenu_item">
                    <span class="navlink_icon">
                        <i class="fa-sharp fa-solid fa-cake-candles"></i>
                    </span>
                    <span class="navlink">Cumpleaños</span>
                    <i class="bx bx-chevron-right arrow-left"></i>
                </div>
                <ul class="menu_items submenu">
                    <li><a href="/code/birthday/showBirthday.php" class="nav_link sublink">Ver Cumpleaños</a></li>
                </ul>
            </li>

            <hr style="border: 1px solid #F3840D; border-radius: 5px;">

            <!-- Mi Cuenta -->
            <li class="item">
                <div class="nav_link submenu_item">
                    <span class="navlink_icon">
                        <i class="fa-solid fa-screwdriver-wrench"></i>
                    </span>
                    <span class="navlink">Mi Cuenta</span>
                    <i class="bx bx-chevron-right arrow-left"></i>
                </div>
                <ul class="menu_items submenu">
                    <li><a href="/reset-password.php" class="nav_link sublink">Cambiar Contraseña</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const submenuItems = document.querySelectorAll(".submenu_item");

    submenuItems.forEach((item) => {
        item.addEventListener("click", function () {
            const parent = this.parentElement;
            parent.classList.toggle("active");
        });
    });
});
document.addEventListener("DOMContentLoaded", function() {
    const toggleButton = document.querySelector(".menu-toggle");
    const sidebar = document.querySelector(".sidebar");

    toggleButton.addEventListener("click", function() {
        sidebar.classList.toggle("show"); // Muestra u oculta la sidebar
    });
});
</script>