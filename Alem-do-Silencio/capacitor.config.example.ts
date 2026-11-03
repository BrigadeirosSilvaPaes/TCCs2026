import { CapacitorConfig } from '@capacitor/cli';

const config: CapacitorConfig = {
  appId: 'com.escola.relato',
  appName: 'Relato de Bullying Escolar',
  webDir: 'dist',
  server: {
    androidScheme: 'https'
  }
};

export default config;
