import { Component, OnInit } from '@angular/core';
import { ServicioService } from '../servicio.service';
import { Usuario } from '../model/usuario';
import { CookieService } from 'ngx-cookie-service';
import {  Router } from '@angular/router';
import { Juego } from '../model/juego';

@Component({
  selector: 'app-admin',
  templateUrl: './admin.component.html',
  styleUrls: ['./admin.component.css']
})
export class AdminComponent implements OnInit{
  usuarios: Usuario[] = [];
  juegos: Juego[] = [];

  constructor(private servicioService: ServicioService) {}

  ngOnInit(): void {
    this.cargarUsuarios();
    this.cargarJuegos();
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
}

export class AuthService {
  constructor(private cookieService: CookieService) {}

  isAdmin(): boolean {
    return this.cookieService.get('admin') === '1';
  }
}
export class AdminGuard  {
  constructor(private authService: AuthService, private router: Router) {}

  canActivate(): boolean {
    if (!this.authService.isAdmin()) {
      this.router.navigate(['/principal']);
      return false;
    }
    return true;
  }
}
