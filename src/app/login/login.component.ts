import { Component, OnInit,ViewEncapsulation } from '@angular/core';
import { AuthService } from 'src/app/auth.service';
import { Router } from '@angular/router';
import { Title } from '@angular/platform-browser';

declare var jquery:any;
declare var $ :any;
@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.css'],
  encapsulation: ViewEncapsulation.None
})
export class LoginComponent implements OnInit {
  authenticated = false;
  constructor(private auth: AuthService,private router: Router,private titleService: Title) { }
  public setTitle( newTitle: string) {
    this.titleService.setTitle(newTitle);
  }
  ngOnInit() {
    this.auth.isLoggedIn().subscribe(data =>{
      this.authenticated = data.isAuth;
    });
    if(this.authenticated)
      this.router.navigate([''])
    this.setTitle('Authentification')
  }
}
