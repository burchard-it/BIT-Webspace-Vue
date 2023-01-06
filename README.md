# BIT Webspace

A [Symfony][symfony] project started in July 2017.

## Version 6

This Version was started in December 2022.  
It was started from scratch with [Symfony][symfony] 6.2.  
The goal was to develop an up-to-date version with [Vue 3][vue] or [Vuetify 3][vuetify] support.

Thanks to [Hugues Tavernier][lhapaipai], connecting Vite to Symfony is quite easy.  
More details regarding the Symfony integration with Vite can be found at the [GitHub repository][symfony-vite-bundle].

After the basic setup I combined this project with the current setup auf a Vue 3 project.

<details style="background-color: #2c3e50; padding: 1rem">
  <summary>Vue project setup</summary>

To create a Vue 3 project run `npm init vue@latest`, **but don't do that in the [Symfony][symfony] project folder**.

<pre>
Vue.js - The Progressive JavaScript Framework

✔ Project name: … vue-project
✔ Add TypeScript? … No / <span style="color: aqua">Yes</span>
✔ Add JSX Support? … <span style="color: aqua">No</span> / Yes
✔ Add Vue Router for Single Page Application development? … No / <span style="color: aqua">Yes</span>
✔ Add Pinia for state management? … <span style="color: aqua">No</span> / Yes
✔ Add Vitest for Unit Testing? … No / <span style="color: aqua">Yes</span>
✔ Add an End-to-End Testing Solution? › <span style="color: aqua">No</span>
✔ Add ESLint for code quality? … No / <span style="color: aqua">Yes</span>
✔ Add Prettier for code formatting? … <span style="color: aqua">No</span> / Yes

Scaffolding project in /.../vue-project...

Done.
</pre>
</details>

## Usage
In the `package.json` there are several scripts that are hopefully self-explanatory.

- **composer:install** is used to install all needed Symfony components
- **dev** is used to start the Vite Dev-Server
- **symfony** is used to start the Symfony Application

[lhapaipai]: https://github.com/lhapaipai
[symfony]: https://symfony.com/
[symfony-vite-bundle]: https://github.com/lhapaipai/vite-bundle
[vue]: https://vuejs.org/
[vuetify]: https://next.vuetifyjs.com/en/getting-started/installation/
