<template>
    <section class="relative w-full h-screen overflow-hidden">
      <!-- Video Background -->
      <video
        autoplay
        muted
        loop
        id="myVideo"
        :src="videoSource"
        class="absolute top-0 left-0 w-full h-full object-cover"
      ></video>
  
      <!-- Overlay Content -->
      <div class="absolute top-0 left-0 w-full h-full flex flex-col justify-center items-center bg-gradient-to-b from-black/50 to-transparent text-white text-center z-10">
        <h1 class="text-4xl font-bold">Selamat Datang di Portal Resmi</h1>
        <h2 class="text-2xl font-semibold mt-2">Pemerintah Kabupaten Madiun</h2>
    
      </div>
  
      <!-- Smooth Transition Gradient -->
      <div class="absolute bottom-0 w-full h-56 bg-gradient-to-t from-white via-white/90 to-transparent"></div>
    </section>
  </template>
  
  <script>
  export default {
    props: {
      videoSource: {
        type: String,
        required: true,
      },
    },
  };
  </script>
  
  <style scoped>
  /* Smooth Video Rendering */
  #myVideo {
    -webkit-transform: translate3d(0, 0, 0);
    transform: translate3d(0, 0, 0);
    will-change: transform, opacity;
    object-fit: cover;
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
  }
  </style>
  