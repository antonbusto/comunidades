<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
    <title>Listas Desplegables con Select Dependientes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            font-size: 18px; /* Tamaño de letra más grande */
        }

        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #fff;
            font-size: 16px; /* Tamaño de letra más grande */
        }

        select[disabled] {
            opacity: 0.6;
        }
    </style>	
</head>
<body>
<div class="container">
    <?php
    // Archivo de conexión a la base de datos
    include 'conexion.php';

    // Función para obtener todas las comunidades
    function getComunidades() {
	// global para permitir que la variable $conn sea accesible dentro de la función	
        global $conn;
        $query = "SELECT * FROM comunidades";
        $result = mysqli_query($conn, $query);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    // Función para obtener provincias por comunidad
    function getProvinciasByComunidad($comunidadId) {
        global $conn;
        $query = "SELECT * FROM provincias WHERE idcomunidad = $comunidadId";
        $result = mysqli_query($conn, $query);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    // Función para obtener municipios por provincia
    function getMunicipiosByProvincia($provinciaId) {
        global $conn;
        $query = "SELECT * FROM municipios WHERE idprovincia = $provinciaId";
        $result = mysqli_query($conn, $query);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    $comunidades = getComunidades();
    ?>

    <form>
        <label>Comunidad:</label>
        <select id="comunidadSelect" onchange="loadProvincias()">
            <option value="">Selecciona una comunidad</option>
            <?php foreach ($comunidades as $comunidad) : ?>
                <option value="<?= $comunidad['idcomunidad'] ?>"><?= $comunidad['comunidad'] ?></option>
            <?php endforeach; ?>
        </select>
        <br>

        <label>Provincia:</label>
        <select id="provinciaSelect" onchange="loadMunicipios()" disabled>
            <option value="">Selecciona una provincia</option>
        </select>
        <br>

        <label>Municipio:</label>
        <select id="municipioSelect" disabled>
            <option value="">Selecciona un municipio</option>
        </select>
    </form>
</div>
    <script>
        function loadProvincias() {
            let comunidadId = document.getElementById("comunidadSelect").value;
            if (comunidadId !== "") {
                fetch("get_provincias.php?comunidad_id=" + comunidadId)
                .then(response => response.json())
                .then(provincias => {
                    let provinciaSelect = document.getElementById("provinciaSelect");
                    provinciaSelect.innerHTML = '<option value="">Selecciona una provincia</option>';
                    provinciaSelect.disabled = false;
                    provincias.forEach(provincia => {
                        provinciaSelect.innerHTML += '<option value="' + provincia.idprovincia + '">' + provincia.provincia + '</option>';
                    });
                })
                .catch(error => console.error('Error:', error));
            } else {
                document.getElementById("provinciaSelect").innerHTML = '<option value="">Selecciona una provincia</option>';
                document.getElementById("provinciaSelect").disabled = true;
                document.getElementById("municipioSelect").innerHTML = '<option value="">Selecciona un municipio</option>';
                document.getElementById("municipioSelect").disabled = true;
            }
        }

        function loadMunicipios() {
            let provinciaId = document.getElementById("provinciaSelect").value;
            if (provinciaId !== "") {
                fetch("get_municipios.php?provincia_id=" + provinciaId)
                .then(response => response.json())
                .then(municipios => {
                    let municipioSelect = document.getElementById("municipioSelect");
                    municipioSelect.innerHTML = '<option value="">Selecciona un municipio</option>';
                    municipioSelect.disabled = false;
                    municipios.forEach(municipio => {
                        municipioSelect.innerHTML += '<option value="' + municipio.idmunicipio + '">' + municipio.municipio + '</option>';
                    });
                })
                .catch(error => console.error('Error:', error));
            } else {
                document.getElementById("municipioSelect").innerHTML = '<option value="">Selecciona un municipio</option>';
                document.getElementById("municipioSelect").disabled = true;
            }
        }
    </script>
</body>
</html>