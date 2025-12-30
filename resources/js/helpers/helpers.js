export function capitalizeWords(event) {
    // 1. Obtener el valor actual del input
    let value = event

    // 2. Aplicar la lógica de capitalización:
    if (value) {
        value = value.toLowerCase().split(' ')
            .map((word) => {
                // Si la palabra no está vacía, capitaliza la primera letra
                if (word.length > 0) {
                    return word.charAt(0).toUpperCase() + word.slice(1);
                }
                return ''; // Maneja espacios dobles o palabras vacías
            })
            .join(' ');
    }

    // 3. Actualizar directamente el modelo de datos
    return value;
}

export function normalizeText(event) {
    // 1. Obtener el valor actual del input
    let value = event;

    // 2. Aplicar la lógica de normalización
    if (value) {
        // .toLowerCase(): Convierte toda la cadena a minúsculas
        // .replace(/\s/g, ''): Busca todas las ocurrencias de espacios en blanco (\s) 
        //                        de forma global (g) y los reemplaza por una cadena vacía ('').
        value = value.toLowerCase().replace(/\s/g, '');
    }

    // 3. Actualizar directamente el modelo de datos
    //form[event.target.name] = value;
    return value;
}
export function getBase64(file) {
  return new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = () => resolve(reader.result);
    reader.onerror = error => reject(error);
  });
}
export function goBack() {
    if (window.history.length > 1) {
        window.history.back();
    } else {
        router.push('/'); // Ruta por defecto si no hay historial
    }
}; 