export function gerarProtocolo(): string {
  const timestamp = Date.now();
  const random = Math.floor(Math.random() * 10000).toString().padStart(4, '0');
  return `REL-${timestamp}-${random}`;
}
