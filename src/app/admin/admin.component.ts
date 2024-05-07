import { Component, OnInit } from '@angular/core';
import { ServicioService } from '../servicio.service';
import { Usuario } from '../model/usuario';
import { CookieService } from 'ngx-cookie-service';
import {  Router } from '@angular/router';
import { Juego } from '../model/juego';
import { FormBuilder, FormGroup, FormArray, Validators, FormControl } from '@angular/forms';

@Component({
  selector: 'app-admin',
  templateUrl: './admin.component.html',
  styleUrls: ['./admin.component.css']
})
export class AdminComponent implements OnInit{
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

  modificarUsuario() {
    // Verifica que el formulario es válido o que los campos requeridos están presentes
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


  eliminarUsuario() {
    const id = this.usuarioForm.get('id')!.value; // Obtiene el ID desde el formulario
    if (!id) {
      alert('Por favor ingrese un ID válido.');
      return;
    }
    if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
      this.servicioService.eliminarUsuario(id).subscribe({
        next: (response) => {
          if (response.success) {
            alert('Usuario eliminado correctamente');
            this.usuarioForm.reset(); // Opcional: resetea el formulario tras la eliminación
          } else {
            alert(response.error); // Aquí podrías especificar un mensaje más claro si es necesario
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
      this.servicioService.agregarJuego(this.juegoForm.value).subscribe({
        next: (response: any) => {
          if (response.success) {
            alert('¡El juego fue insertado con éxito!');
            this.juegoForm.reset(); // Restablecer el formulario después de la inserción exitosa
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