import {Component, ElementRef, ViewChild} from '@angular/core';
import {HttpClient} from "@angular/common/http";

@Component({
    selector: 'app-login',
    standalone: true,
    imports: [],
    templateUrl: './login.component.html',
    styleUrl: './login.component.css'
})
export class LoginComponent {

    @ViewChild('flipper') flipperRef !: ElementRef;

    constructor(private http: HttpClient) {
        this.loginUser();
    }

    //flip login card
    public flipCard() {
        console.log(this.flipperRef);
        this.flipperRef.nativeElement.classList.toggle('flip')
    }

    public loginUser() {
        this.http.post('http://127.0.0.1:8000/api/v1/register-user', {username: 'test', password: 'test'}).subscribe({
            next: (data: any) => {
                // Handle successful response here
                console.log("Login successful:", data);
            },
            error: (err: any) => {
                // Handle error response here
                console.error("Login error:", err);
            }
        });
    }

}
