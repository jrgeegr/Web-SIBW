document.addEventListener("DOMContentLoaded", () => {
    const formulario = document.getElementById("form-busqueda-noticias");
    const cuerpoTabla = document.getElementById("cuerpo-tabla-noticias");

    // 1. LÓGICA DE BÚSQUEDA TRIPLE ASÍNCRONA (Paso 3 actualizado para incluir la columna publicado)
    formulario.addEventListener("submit", (e) => {
        e.preventDefault();

        const titulo = document.getElementById("busqueda_titulo").value.trim();
        const cuerpo = document.getElementById("busqueda_cuerpo").value.trim();
        const hashtag = document.getElementById("busqueda_hashtag").value.trim();

        const url = `gestion_noticias.php?ajax=1&busqueda_titulo=${encodeURIComponent(titulo)}&busqueda_cuerpo=${encodeURIComponent(cuerpo)}&busqueda_hashtag=${encodeURIComponent(hashtag)}`;

        fetch(url)
            .then(response => {
                if (!response.ok) throw new Error("Error en la búsqueda asíncrona");
                return response.json();
            })
            .then(noticias => {
                cuerpoTabla.innerHTML = "";

                if (noticias.length === 0) {
                    cuerpoTabla.innerHTML = `<tr><td colspan="5" class="texto-centrado">No se encontraron noticias con los filtros aplicados.</td></tr>`;
                    return;
                }

                noticias.forEach(n => {
                    let tagsHTML = `<span class="sin-datos">Ninguno</span>`;
                    if (n.lista_hashtags) {
                        const arrayTags = n.lista_hashtags.split(', ');
                        tagsHTML = `<span class="tags-tabla">`;
                        arrayTags.forEach(tag => {
                            tagsHTML += `#${tag} `;
                        });
                        tagsHTML += `</span>`;
                    }

                    // Comprobamos si debe estar marcado el checkbox
                    const isChecked = parseInt(n.publicado) === 1 ? 'checked' : '';

                    const fila = document.createElement("tr");
                    fila.innerHTML = `
                        <td>${n.titulo}</td>
                        <td>${n.fecha_pub}</td>
                        <td>${tagsHTML}</td>
                        <td class="texto-centrado">
                            <input type="checkbox" class="check-publicado" data-id="${n.id}" ${isChecked}>
                        </td>
                        <td>
                            <a href="editar_noticia.php?id=${n.id}">Editar</a> | 
                            <a href="gestion_noticias.php?borrar=${n.id}" onclick="return confirm('¿Borrar noticia?')">Borrar</a>
                        </td>
                    `;
                    cuerpoTabla.appendChild(fila);
                });
            })
            .catch(error => console.error("Error en la petición asíncrona:", error));
    });

    // 2. LÓGICA ASÍNCRONA PARA EL CHECKBOX 
    // Usamos delegación de eventos: escuchamos en el tbody cualquier cambio ('change')
    cuerpoTabla.addEventListener("change", (e) => {
        // Comprobamos si el elemento que ha cambiado es uno de nuestros checkboxes de publicación
        if (e.target && e.target.classList.contains("check-publicado")) {
            const checkbox = e.target;
            const idNoticia = checkbox.getAttribute("data-id");
            
            // Si está marcado enviamos 1, si no, enviamos 0
            const nuevoEstado = checkbox.checked ? 1 : 0;

            // Deshabilitamos el checkbox momentáneamente para evitar clics repetidos rápidos
            checkbox.disabled = true;

            // Lanzamos la petición fetch en segundo plano
            fetch(`gestion_noticias.php?cambiar_publicado=1&id_noticia=${idNoticia}&estado=${nuevoEstado}`)
                .then(response => {
                    if (!response.ok) throw new Error("Error de red al actualizar estado");
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        console.log(`Noticia ${idNoticia} actualizada con éxito a estado: ${nuevoEstado}`);
                    } else {
                        alert("No se pudo actualizar en el servidor: " + data.mensaje);
                        // Revertimos el cambio visual si hubo un error en la BD
                        checkbox.checked = !checkbox.checked;
                    }
                })
                .catch(error => {
                    console.error("Error en la petición de publicación:", error);
                    alert("Ocurrió un error de conexión al cambiar el estado.");
                    checkbox.checked = !checkbox.checked;
                })
                .finally(() => {
                    // Volvemos a habilitar el checkbox
                    checkbox.disabled = false;
                });
        }
    });
});