import { Component } from '@angular/core';
import { FormBuilder, FormGroup, Validators, AbstractControl } from '@angular/forms';

import { Usuario } from '../model/usuario';
import { ServicioService } from '../servicio.service';
import { Observable } from 'rxjs';

@Component({
  selector: 'app-newlog',
  templateUrl: './newlog.component.html',
  styleUrls: ['./newlog.component.css']
})
export class NewlogComponent {
  newusuario: Usuario = {
    id: 0,
    nombre: '',
    email: '',
    clave: '',
    admin: '0',
  };
  newusuarioForm!: FormGroup;
  public message: string = '';
  public clasec: string = '';
  public clases: string = 'text-info';
  resp: any;
  actuales$!: Observable<Usuario[]>;
  showPassword = false;
  showConfirmPassword = false;

  constructor(
    private servicioService: ServicioService,
    private fb: FormBuilder
  ) {
    this.newusuarioForm = this.fb.group({
      nombre: ['', [Validators.required]],
      email: ['', [Validators.required, Validators.email]],
      clave: ['', [
        Validators.required,
        Validators.pattern(/^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*.])[A-Za-z\d!@#$%^&*.]{8,}$/)
      ]],
      confirmarClave: ['', [Validators.required]]
    }, { validators: this.passwordMatchValidator });
  }

  entradausuario() {
    if (this.newusuarioForm.invalid) {
      this.message = 'Por favor corrige los errores';
      this.clasec = 'text-danger';
    } else {
      this.clasec = 'text-success';
      this.newusuario = this.newusuarioForm.value;
      console.log('Entrada correcta', this.newusuario);
      this.servicioService
        .postDato(this.newusuario)
        .subscribe({
          next: resp => this.resp = resp,
          error: err => console.log(err),
          complete: () => this.actuales$ = this.servicioService.getDatosUsuario()
        });
    }
  }

  passwordMatchValidator(group: AbstractControl): { [key: string]: boolean } | null {
    const password = group.get('clave');
    const confirmPassword = group.get('confirmarClave');
    if (password?.value !== confirmPassword?.value) {
      confirmPassword?.setErrors({ noMatch: true });
      return { noMatch: true };
    }
    return null;
  }

  togglePasswordVisibility() {
    this.showPassword = !this.showPassword;
  }

  toggleConfirmPasswordVisibility() {
    this.showConfirmPassword = !this.showConfirmPassword;
  }

  get nombre() {
    return this.newusuarioForm.get('nombre');
  }

  get email() {
    return this.newusuarioForm.get('email');
  }

  get clave() {
    return this.newusuarioForm.get('clave');
  }

  get confirmarClave() {
    return this.newusuarioForm.get('confirmarClave');
  }
}
