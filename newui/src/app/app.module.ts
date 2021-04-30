import {NgModule} from '@angular/core';
import {BrowserModule} from '@angular/platform-browser';
import {
    TuiNotificationsModule,
    TuiDialogModule,
    TuiRootModule,
} from '@taiga-ui/core';
import {AppRoutingModule} from './app-routing.module';
import {AppComponent} from './app.component';
import {iconsPathFactory, TUI_ICONS_PATH} from '@taiga-ui/core';
import {TuiIslandModule} from '@taiga-ui/core';
import {ChangeDetectionStrategy, Component} from '@angular/core';
import {TUI_IS_ANDROID, TUI_IS_IOS} from '@taiga-ui/cdk';
import { LandingComponent } from './landing/landing.component';

@NgModule({
    declarations: [
        AppComponent,
        LandingComponent
    ],
    imports: [
        BrowserModule,
        TuiRootModule,
        TuiIslandModule,
        TuiNotificationsModule,
        TuiDialogModule,
        AppRoutingModule,
    ],
    providers: [
        {
            provide: TUI_ICONS_PATH,
            useValue: iconsPathFactory('assets/taiga-ui/icons/'),
        },
        {
          provide: TUI_IS_IOS,
          useValue: false,
        },
        {
            provide: TUI_IS_ANDROID,
            useValue: false,
        },
    ],
    bootstrap: [AppComponent]
})
export class AppModule {
  readonly collaborators = ['Carol Cleveland', 'Neil Innes'];

  readonly tabs = [
      'John Cleese',
      'Eric Idle',
      this.collaborators,
      'Michael Palin',
      'Terry Jones',
      'Terry Gilliam',
      'Graham Chapman',
  ];

  activeElement = String(this.tabs[0]);

  open = false;

  get activeItemIndex(): number {
      if (this.collaborators.indexOf(this.activeElement) !== -1) {
          return this.tabs.indexOf(this.collaborators);
      }

      return this.tabs.indexOf(this.activeElement);
  }

  stop(event: Event) {
      // We need to stop tab custom event so parent component does not think its active
      event.stopPropagation();
  }

  onClick(activeElement: string) {
      this.activeElement = activeElement;
      this.open = false;
  }

  isString(tab: any): boolean {
      return typeof tab === 'string';
  }
}