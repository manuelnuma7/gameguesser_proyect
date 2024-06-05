import { Component, OnInit } from '@angular/core';
import { ServicioService } from '../servicio.service';
import { Usuario } from '../model/usuario';
import { CookieService } from 'ngx-cookie-service';
import { Router } from '@angular/router';
import { Juego } from '../model/juego';
import { FormBuilder, FormGroup, FormArray, Validators, FormControl } from '@angular/forms';

const IMAGE_BASE_PATH = '../assets/images/gameimg/';

@Component({
  selector: 'app-admin',
  templateUrl: './admin.component.html',
  styleUrls: ['./admin.component.css']
})
export class AdminComponent implements OnInit {
  usuarios: Usuario[] = [];
  juegos: Juego[] = [];
  activeSection: string = '';
  juegoForm!: FormGroup;
  generosDisponibles: string[] = [];
  plataformasDisponibles: string[] = [];

  usuarioForm = new FormGroup({
    id: new FormControl(),
    nombre: new FormControl(''),
    email: new FormControl(''),
    clave: new FormControl(''),
    admin: new FormControl('')
  });

  constructor(
    private servicioService: ServicioService,
    private cookieService: CookieService,
    private router: Router,
    private fb: FormBuilder
  ) {}

  ngOnInit(): void {
    this.checkAdmin();
    this.cargarUsuarios();
    this.cargarJuegos();

    this.juegoForm = this.fb.group({
      id: ['', Validators.required],  // Asegúrate de que el control 'id' está aquí
      nombre: ['', Validators.required],
      fecha: ['', [Validators.required, Validators.min(1972), Validators.max(new Date().getFullYear())]],
      nota: ['', [Validators.required, Validators.min(0), Validators.max(99)]],
      companion: ['', Validators.required],
      nivel: ['', Validators.required],
      generos: this.fb.array([]),
      plataformas: this.fb.array([]),
      imagenes: this.fb.array([]),
      musica: this.fb.array([])
    });

    this.servicioService.getGeneros().subscribe((generos: string[]) => {
      this.generosDisponibles = generos;
    });

    this.servicioService.getPlataformas().subscribe((plataformas: string[]) => {
      this.plataformasDisponibles = plataformas;
    });
  }

  cargarUsuarios() {
    this.servicioService.getDatosUsuario().subscribe(usuarios => {
      this.usuarios = usuarios;
    });
  }

  cargarJuegos() { 
    this.servicioService.getDatosJuego().subscribe(juegos => {
      this.juegos = juegos;
    });
  }

  setActiveSection(section: string) {
    this.activeSection = section;
  }

  checkAdmin() {
    if (this.cookieService.get('admin') !== '1') {
      this.router.navigate(['/principal']);
    }
  }

  cargarUsuario() {
    const id = this.usuarioForm.get('id')!.value as number;
    this.servicioService.getUsuarioById(id).subscribe((usuario) => {
      this.usuarioForm.patchValue({
        nombre: usuario.nombre,
        email: usuario.email,
        clave: usuario.clave,
        admin: usuario.admin,
      });
    });
  }

  cargarJuego() {
    const idControl = this.juegoForm.get('id');
    if (!idControl) {
      alert('Por favor, ingrese un ID válido.');
      return;
    }
  
    const id = idControl.value as number;
    this.servicioService.getJuegoById(id).subscribe((juego) => {
      this.juegoForm.patchValue({
        nombre: juego.nombre,
        fecha: juego.fecha,
        nota: juego.nota,
        companion: juego.companion,
        nivel: juego.nivel
      });
  
      this.generos.clear();
      this.plataformas.clear();
      this.imagenes.clear();
      this.musica.clear();
  
      juego.generos.forEach(genero => this.generos.push(this.fb.control(genero)));
      juego.plataformas.forEach(plataforma => this.plataformas.push(this.fb.control(plataforma)));
      // Eliminar la ruta base de las imágenes antes de rellenar el formulario
      juego.imagenes.forEach(imagen => {
        const nombreImagen = imagen!.replace(IMAGE_BASE_PATH, '');
        this.imagenes.push(this.fb.control(nombreImagen));
      });
      juego.musica.forEach(musica => this.musica.push(this.fb.control(musica)));
    });
  }

  modificarUsuario() {
    if (this.usuarioForm.valid) {
      const usuario: Usuario = this.usuarioForm.value as Usuario;
      this.servicioService.modificarUsuario(usuario).subscribe({
        next: (response) => {
          if (response.success) {
            alert('Registro modificado correctamente');
          } else if (response.error) {
            alert(`Error: ${response.error.msg}`);
          }
        },
        error: (err) => {
          alert(`Error en la comunicación con el servidor: ${err.message}`);
        }
      });
    } else {
      alert('Por favor, completa todos los campos del formulario correctamente.');
    }
  }

  modificarJuego() {
    if (this.juegoForm.valid) {
      const juego: Juego = this.juegoForm.value as Juego;
      
      // Añadir la ruta base a las imágenes antes de enviar los datos al servidor y filtrar nulos
      juego.imagenes = juego.imagenes.filter(img => img).map(img => IMAGE_BASE_PATH + img);
      
      // Filtrar nulos en música
      juego.musica = juego.musica.filter(mus => mus);
  
      this.servicioService.modificarJuego(juego).subscribe({
        next: (response) => {
          if (response.success) {
            alert('Juego modificado correctamente');
          } else if (response.error) {
            alert(`Error: ${response.error}`);
          } else {
            alert('Error desconocido al modificar el juego');
          }
        },
        error: (err) => {
          alert(`Error en la comunicación con el servidor: ${err.message}`);
        }
      });
    } else {
      alert('Por favor, completa todos los campos del formulario correctamente.');
    }
  }

  eliminarJuego() {
    const id = this.juegoForm.get('id')!.value; 
    if (!id) {
      alert('Por favor ingrese un ID válido.');
      return;
    }
    if (confirm('¿Estás seguro de que deseas eliminar este juego?')) {
      this.servicioService.eliminarJuego(id).subscribe({
        next: (response) => {
          if (response.success) {
            alert('Juego eliminado correctamente');
            this.juegoForm.reset(); 
          } else {
            alert(response.error); 
          }
        },
        error: (error) => {
          alert('Ha ocurrido un error al tratar de eliminar el juego: ' + error.message);
        }
      });
    }
  }

  eliminarUsuario() {
    const id = this.usuarioForm.get('id')!.value; 
    if (!id) {
      alert('Por favor ingrese un ID válido.');
      return;
    }
    if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
      this.servicioService.eliminarUsuario(id).subscribe({
        next: (response) => {
          if (response.success) {
            alert('Usuario eliminado correctamente');
            this.usuarioForm.reset(); 
          } else {
            alert(response.error); 
          }
        },
        error: (error) => {
          alert('Ha ocurrido un error al tratar de eliminar el usuario: ' + error.message);
        }
      });
    }
  }

  agregarGenero() {
    if (this.generos.length < 3) {
      this.generos.push(this.fb.control('', Validators.required));
    }
  }

  agregarPlataforma() {
    if (this.plataformas.length < 3) {
      this.plataformas.push(this.fb.control('', Validators.required));
    }
  }

  agregarImagen() {
    if (this.imagenes.length < 5) {
      this.imagenes.push(this.fb.control(''));
    }
  }

  agregarMusica() {
    if (this.musica.length < 3) {
      this.musica.push(this.fb.control(''));
    }
  }

  get generos() {
    return this.juegoForm.get('generos') as FormArray;
  }

  get plataformas() {
    return this.juegoForm.get('plataformas') as FormArray;
  }

  get imagenes() {
    return this.juegoForm.get('imagenes') as FormArray;
  }

  get musica() {
    return this.juegoForm.get('musica') as FormArray;
  }

  onSubmit() {
    if (this.juegoForm.valid) {
      const juego: Juego = this.juegoForm.value as Juego;
      juego.imagenes = juego.imagenes.map(img => IMAGE_BASE_PATH + img);
      this.servicioService.agregarJuego(juego).subscribe({
        next: (response: any) => {
          if (response.success) {
            alert('¡El juego fue insertado con éxito!');
            this.juegoForm.reset(); 
          } else if (response.error) {
            alert(`Error: ${response.error}`);
          }
        },
        error: (err) => {
          alert(`Error en la comunicación con el servidor: ${err.message}`);
        }
      });
    } else {
      alert('Por favor, completa todos los campos requeridos.');
    }
  }

  resetForm() {
    this.juegoForm.reset();
  }
}
