import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, of } from 'rxjs';
import { environment } from '../environments/environment';
import { Router } from '@angular/router';
let apiUrl = environment.apiurl;
interface authenticated {
  isAuth: boolean
}
@Injectable()
export class AuthService {
  private loggedInStatus = false
  constructor(private http: HttpClient,private router: Router) { }
  isLoggedIn() {
    return this.http.get<authenticated>(apiUrl+'isLoggedIn.php')
  }
}
