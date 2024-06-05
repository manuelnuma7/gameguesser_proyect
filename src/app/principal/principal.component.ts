import { Component, ElementRef, ViewChild, AfterViewInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Usuario } from '../model/usuario';
import { ServicioService } from '../servicio.service';
import { CookieService } from 'ngx-cookie-service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-principal',
  templateUrl: './principal.component.html',
  styleUrls: ['./principal.component.css']
})
export class PrincipalComponent implements AfterViewInit {
  newloginForm!: FormGroup;
  newlogin!: Usuario;
  isLoggedIn = false;
  entrada: boolean = false;
  bienvenida: string = "";

  @ViewChild('welcomeMessageElement', { static: true }) welcomeMessageElement!: ElementRef;

  fallo: boolean = false;
  aceptarCookies = false;
  fadeOut: boolean = false;
  slideOut: boolean = false;

  constructor(
    private servicioService: ServicioService,
    private fb: FormBuilder,
    private router: Router,
    private cookieService: CookieService,
  ) {
    this.newloginForm = this.fb.group({
      nombre: ['', [Validators.required]],
      clave: ['', [Validators.required]]
    });
  }

  get nombre() {
    return this.newloginForm.get('nombre');
  }

  get clave() {
    return this.newloginForm.get('clave');
  }

  ngAfterViewInit() {
    const sessionCookieExists = this.cookieService.check('session');
    if (sessionCookieExists) {
      const valorCookie = this.cookieService.get('session');
      if (this.welcomeMessageElement) { // Verificar si welcomeMessageElement está definido
        this.bienvenida = this.welcomeMessageElement.nativeElement.textContent = `Bienvenido ${valorCookie}`;
      }
      this.isLoggedIn = true;
    }
    const politicaCookieExists = this.cookieService.check('Politica');
    if (politicaCookieExists) {
      this.aceptarCookies = true;
    }
  }

  entradalogin() {
    this.newlogin = this.newloginForm.value;
    this.servicioService.login(this.newlogin).subscribe((data) => {
      console.log(data);
      if (data.length > 0) {
        const currentDate = new Date();
        const expirationDate = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, currentDate.getDate());
        this.cookieService.set('session', data[0].nombre, expirationDate);
        this.cookieService.set('admin', data[0].admin, expirationDate);
        this.isLoggedIn = true;
        this.router.navigateByUrl('jugar');
      } else {
        this.fallo = true;
      }
    });
  }

  aceptarPoliticaCookies() {
    const currentDate = new Date();
    const expirationDate = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, currentDate.getDate());
    this.cookieService.set('Politica', 'aceptada', expirationDate);
    this.aceptarCookies = true;
    this.animateClose();
  }

  rechazarPoliticaCookies() {
    this.animateClose();
  }

  animateClose() {
    this.fadeOut = true;
    this.slideOut = true;
    setTimeout(() => {
      this.aceptarCookies = true;
    }, 500); // Duración de la animación
  }

  checkSessionCookie(): boolean {
    const sessionCookie = document.cookie.includes('Politica');
    return sessionCookie;
  }

  checkadmin() {
    const valorCookie = this.cookieService.get('admin');
    if (valorCookie == '1') {
      return true;
    } else {
      return false;
    }
  }
}
