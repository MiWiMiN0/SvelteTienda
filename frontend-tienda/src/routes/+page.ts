import type { PageLoad } from './$types';

export interface Producto {
    id: number;
    nombre: string;
    descripcion: string;
    precio: number;
    imagen: string | null;
}

export const load: PageLoad = async ({ fetch }) => {
    try {
        
        const response = await fetch('http://localhost:8000/api/productos', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error(`Falla en la API: Código de estado ${response.status}`);
        }

        const data = await response.json();

       
        const productos: Producto[] = data.data || data; 

        return {
            productos
        };
    } catch (error) {
        console.error("Vulnerabilidad de red o falla de conexión:", error);
        
        return {
            productos: [] 
        };
    }
};