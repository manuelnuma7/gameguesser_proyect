
export interface Juego {
    id:          number;
    nombre:      string;
    fecha:       number;
    nota:        number;
    companion:   string;
    nivel:       number;
    generos:     string[];
    plataformas: string[];
    musica:      Array<null | string>;  //3 huecos 
    imagenes:    Array<null | string>;  //5 huecos
}
