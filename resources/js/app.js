import './bootstrap';
import 'preline';

import barba from '@barba/core';

import Swup from 'swup';
import SwupLivewirePlugin from '@swup/livewire-plugin';
import SwupFormsPlugin from '@swup/forms-plugin';
import SwupFadeTheme from '@swup/fade-theme';

const swup = new Swup({
    containers: ["#swup"],
    plugins: [
        new SwupLivewirePlugin(),
        new SwupFormsPlugin(),
        new SwupFadeTheme()
    ],
  });




  barba.init({
    transitions: [{
      name: 'opacity-transition',
      leave(data) {
        return gsap.to(data.current.container, {
          opacity: 0
        });
      },
      enter(data) {
        return gsap.from(data.next.container, {
          opacity: 0
        });
      }
    }],
  });
