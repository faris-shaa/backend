/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./src/**/*.{html,js}",
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      fontFamily: {
        heading: ['Bricolage Grotesque', 'Alexandria', 'sans-serif'],
        body: ['Inter', 'Alexandria', 'sans-serif'],
      },
      backgroundImage: {
        'dark-gradient': 'linear-gradient(180deg, #1E1D1E 0%, #272627 100%)',
        'light-gradient': 'linear-gradient(270deg, rgba(114, 57, 149, 0) 0%, rgba(114, 57, 149, 0.12) 100%)',
        'white-gradient': 'linear-gradient(180deg, #f9f9f9 0%, #adadad 100%)',
        'gradient-circle': 'linear-gradient(0deg, #232123, #232123), linear-gradient(180deg, #1E1D1E 0%, #272627 100%)'
      },
      backgroundSize: {
        '120': '120%',
      },
      colors: {
        dark: '#121212',
        dark_1: '#1A181A',
        dark_2: '#1A171A',
        dark_3: '#120D12',
        dark_4: '#292929',
        light: '#FBF9FD',
        gray_b: '#BDBDBD',
        gray_6: '#666666',
        gray_9: '#999999',
        gray_f: '#FBF9FD0D',
        gray_s: '#FBF9FD52',
        gray_35: '#353535',
        gray_b5: '#B5B7C8',
        gray_b12: '#12121252',
        gray_fb: '#FBF9FD1F',
        primary_color_15: '#21112A',
        primary_color_14: '#0B060F',
        pimary_colorr_13: '#170B1E',
        primary_color_12: '#22112D',
        primary_color_11: '#2E173C',
        primary_color_10: '#442259',
        primary_color_9: '#5B2E77',
        primary_color_8: '#723995',
        primary_color_7: '#8D5FAA',
        primary_color_6: '#A986BF',
        primary_color_5: '#C4ACD3',
        primary_color_4: '#D2BFDE',
        primary_color_3: '#E0D3E8',
        primary_color_2: '#EDE6F3',
        primary_color_1: '#FBF9FD',
        primary_color_o10_1: ' #FBF9FD0A',
        primary_color_o10_2: ' #A986BF14',
        primary_color_o25_8: '#72399540',
        primary_color_o25_9: '#FBF9FD14',
        primary_color_0: '#fbf9fd00',
        primary_color_a9: '#A986BF26',
        primary_color_a11: '#FBF9FD1A',

        red: " #E55E73",
        green: " #01E6A0",
        red_light: " #e55e7321",
        yelow: " #C59A00"
      },
      fontSize: {
        'h1': ['2.25rem', { lineHeight: '2.5rem' }], // 36px
        'h2': ['2rem', { lineHeight: '3rem' }], //32px
        'h3': ['1.5rem', { lineHeight: '2rem' }], // 24px
        'h4': ['1.25rem', { lineHeight: '1.90rem' }], // 20px
        'h18': ['1.125rem', { lineHeight: '1.5rem' }], // 18px
        'h5': ['1rem', { lineHeight: '1.5rem' }], // 16px
        'h6': ['0.875rem', { lineHeight: '1.15rem' }], // 14px
        'h7': ['0.75rem', { lineHeight: '1rem' }], // 12px
        'h8': ['0.625rem', { lineHeight: '1rem' }], // 10px
        'h9': ['2.5rem', { lineHeight: '3rem' }], // 
      },
      borderRadius: {
        '5xl': '3.25rem', // 52px
        '6xl': '2.5rem', // 40px
        '1-lr': '0 1rem 1rem 0', //16px
      },
      borderWidth: {
        '1': '1px',
      },
      width: {
        'fit': 'fit-content',
        'w-480': '30rem',
        'w-400': '25rem',
        'w-500': '31.4rem',
        'w-256': '16rem',
        'w-760': '47.5rem',
        'w-32': '2rem',
        'w-60': '3.75rem',

      },
      height: {
        'h-270': '16.875rem',
        'h-424': '26.5rem',
        'h-760': '47.5rem',
        'h-500': '31.4rem',
        'h-256': '16rem',

      },
      spacing: {
        '32': '8rem', // 128px
        '16': '4rem', // 64px
        '8': '2.5rem', // 40px
        '4': '1.5rem', // 24px
        '2': '1rem', // 16px
        '1': '.5rem', //12px
      },
      padding: {
        '4-16': '0.25rem 1rem',
        '12-24': '0.75rem 1.5rem',
        '32-24': '2rem 1.5rem',
        '32-32': '2rem 2rem',
        '22-32': '1.375rem 2rem',
        '6-8': '0.375rem .5rem',
        '6-28': '0.375rem 2rem',
        '24-16': '1.5rem 1rem',
        '16-16': '1rem 1rem',
        'x3': '0 1rem',
        '4-8': '0.25rem  0.5rem ',
        'p32': '2rem',
        'p423': '26.4374rem',
        'p5': '26.4374rem'
      },
      margin: {
        'm-32':'2rem'
      },
      boxShadow: {
        'shadow-dark': '7px 6px 13px #00000061',
      },
      inset: {
        '-50': '-50px',
      },
      container: {
        center: true,
        padding: '1rem',
        screens: {
          DEFAULT: '1184px',
        },
      },
    },
  },
  plugins: [],
}

