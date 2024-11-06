import {Directive, ElementRef, OnInit, Renderer2} from '@angular/core';

@Directive({
  selector: '[appRequiredAstrix]',
  standalone: true
})
export class RequiredAstrixDirective implements OnInit{

  constructor(private elementRef: ElementRef, private renderer: Renderer2) {
  }

  ngOnInit() {

    const spanElement = this.renderer.createElement('span');
    this.renderer.setProperty(spanElement, 'textContent', '*');
    this.renderer.addClass(spanElement, 'required');

    this.renderer.appendChild(this.elementRef.nativeElement, spanElement);
  }

}
