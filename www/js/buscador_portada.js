document.addEventListener("DOMContentLoaded", () => {
    const inputBuscador = document.getElementById("buscador-google");
    const desplegable = document.getElementById("desplegable-coincidencias");

    // Escuchamos cada vez que el usuario levanta una tecla (escribe)
    inputBuscador.addEventListener("input", () => {
        const query = inputBuscador.value.trim();

        // Si el usuario borra todo, ocultamos el desplegable y salimos
        if (query.length < 1) {
            desplegable.innerHTML = "";
            desplegable.style.display = "none";
            return;
        }

        // ¡Petición AJAX usando FETCH moderno!
        fetch(`buscar_noticias_ajax.php?q=${encodeURIComponent(query)}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error("Error en la respuesta del servidor");
                }
                return response.json(); // Convierte la respuesta a un objeto JS
            })
            .then(noticias => {
                // Vaciamos el desplegable antes de pintar los nuevos resultados
                desplegable.innerHTML = "";

                if (noticias.length > 0) {
                    desplegable.style.display = "block";
                    
                    // Recorremos las noticias devueltas por el servidor
                    noticias.forEach(noticia => {
                        const li = document.createElement("li");
                        
                        // Creamos un enlace dinámico hacia noticia.php?id=...
                        const enlace = document.createElement("a");
                        enlace.href = `noticia.php?id=${noticia.id}`;
                        enlace.textContent = noticia.titulo;
                        
                        li.appendChild(enlace);
                        desplegable.appendChild(li);
                    });
                } else {
                    // Si no hay coincidencias, podemos poner un aviso o simplemente ocultarlo
                    desplegable.style.display = "none";
                }
            })
            .catch(error => console.error("Error en Fetch:", error));
    });

    // Cerrar el desplegable si el usuario hace clic fuera del buscador
    document.addEventListener("click", (e) => {
        if (!inputBuscador.contains(e.target) && !desplegable.contains(e.target)) {
            desplegable.style.display = "none";
        }
    });
});