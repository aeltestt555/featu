<template>
  <router-view />
  <button id="installButton" style="display:none;">Install App</button>
</template>

<script>
import { onMounted } from 'vue';

export default {
  name: 'App',
  setup() {
    let deferredPrompt;

    onMounted(() => {
      const installBtn = document.getElementById('installButton');

      if (!installBtn) return;

      window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;
        installBtn.style.display = 'block';
      });

      installBtn.addEventListener('click', async () => {
        if (!deferredPrompt) return;
        deferredPrompt.prompt();
        const choice = await deferredPrompt.userChoice;
        console.log(choice.outcome);
        deferredPrompt = null;
        installBtn.style.display = 'none';
      });
    });
  }
};
</script>

<style>
.app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
</style>
