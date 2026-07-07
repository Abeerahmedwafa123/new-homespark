/**
 * Audio pipeline for Simli — framework-agnostic (pure Web Audio API).
 * Ported verbatim from the original Next.js app (abir-demo/src/app/page.js).
 *
 * Guarantees:
 * - Sample rate: 16,000 Hz (OfflineAudioContext resampling)
 * - Format: PCM16 (Int16 little-endian)
 * - Mono
 * - Chunked frames
 */
export async function downsampleAndChunkAudioPCM16_16kHz(audioUrl, chunkSizeMs = 100) {
  const TARGET_RATE = 16000;

  const res = await fetch(audioUrl);
  if (!res.ok) throw new Error(`Failed to fetch audio: ${res.status}`);
  const arrayBuffer = await res.arrayBuffer();

  const decodeCtx = new (window.AudioContext || window.webkitAudioContext)();
  const decoded = await decodeCtx.decodeAudioData(arrayBuffer);

  // Resample to 16kHz mono
  const frameCount = Math.ceil(decoded.duration * TARGET_RATE);
  const offline = new OfflineAudioContext(1, frameCount, TARGET_RATE);

  const mono = offline.createBuffer(1, decoded.length, decoded.sampleRate);
  mono.copyToChannel(decoded.getChannelData(0), 0);

  const src = offline.createBufferSource();
  src.buffer = mono;
  src.connect(offline.destination);
  src.start(0);

  const rendered = await offline.startRendering();

  if (rendered.sampleRate !== TARGET_RATE) {
    throw new Error(`Resample failed: expected ${TARGET_RATE}, got ${rendered.sampleRate}`);
  }

  const float32 = rendered.getChannelData(0);

  // Float32 -> PCM16
  const pcm16 = new Int16Array(float32.length);
  for (let i = 0; i < float32.length; i++) {
    const s = Math.max(-1, Math.min(1, float32[i]));
    pcm16[i] = s < 0 ? Math.round(s * 0x8000) : Math.round(s * 0x7fff);
  }

  // Chunk
  const samplesPerChunk = Math.floor((TARGET_RATE * chunkSizeMs) / 1000);
  const chunks = [];
  for (let i = 0; i < pcm16.length; i += samplesPerChunk) {
    chunks.push(pcm16.slice(i, i + samplesPerChunk));
  }

  decodeCtx.close?.();

  return {
    sampleRate: TARGET_RATE,
    format: "PCM16",
    chunkSizeMs,
    chunks, // Array<Int16Array>
  };
}
