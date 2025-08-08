<template>
  <FrontendLayout :title="`Certificate - ${certificate.certificate_number}`" :show-breadcrumb="false">
    <div class="certificate-page">
      <div class="certificate-container" id="certificate-content">
        <!-- Multi-layer border frames -->
        <div class="border-frame"></div>
        <div class="inner-border"></div>
        <div class="decorative-border"></div>
        
        <!-- Corner flourishes -->
        <div class="corner-flourish top-left"></div>
        <div class="corner-flourish top-right"></div>
        <div class="corner-flourish bottom-left"></div>
        <div class="corner-flourish bottom-right"></div>
        
        <!-- Main content -->
        <div class="certificate-content">
          <!-- Header -->
          <div class="header-section">
            <div class="islamic-pattern">✦ ✦ ✦</div>
            <div class="academy-name">ইকরা অনলাইন একাডেমি</div>
            <div class="academy-english">IQRA ONLINE ACADEMY</div>
            <div class="tagline">Center of Islamic Learning Excellence</div>
          </div>
          
          <div class="ornamental-divider">◈ ◈ ◈</div>
          
          <!-- Certificate Title -->
          <div class="certificate-title">
            <div class="arabic-text">شهادة التقدير</div>
            <div class="main-title">Certificate of Achievement</div>
            <div class="bengali-title">সনদপত্র</div>
          </div>
          
          <!-- Student Information -->
          <div class="recipient-section">
            <div class="presented-text">This is to certify that</div>
            <div class="student-name">{{ certificate.student_name }}</div>
            <div class="achievement-text">
              has successfully completed the course with distinction
            </div>
          </div>
          
          <!-- Course Information -->
          <div class="course-section">
            <div class="course-title">{{ certificate.course_title }}</div>
            <div v-if="certificate.course_description" class="course-description">
              {{ certificate.course_description }}
            </div>
            
            <!-- Final Score (if available) -->
            <div v-if="certificate.final_score" class="score-section">
              <span class="score-label">Final Score:</span>
              <span class="score-value">{{ certificate.final_score }}%</span>
            </div>
            
            <!-- Instructors -->
            <div v-if="certificate.instructors && certificate.instructors.length > 0" class="instructors-section">
              <div class="instructors-label">Under the guidance of</div>
              <div class="instructors-list">
                <span v-for="(instructor, index) in certificate.instructors" :key="index" class="instructor-name">
                  {{ instructor }}<span v-if="index < certificate.instructors.length - 1">, </span>
                </span>
              </div>
            </div>
            
            <!-- Date -->
            <div class="date-section">
              Awarded on this <span class="date-value">{{ formatDate(certificate.completion_date) }}</span>
            </div>
          </div>
          
          <div class="ornamental-divider">◆ ◆ ◆</div>
          
          <!-- Footer -->
          <div class="footer-section">
            <!-- Signatures -->
            <div class="signatures-container">
              <div class="signature-block">
                <div class="signature-line"></div>
                <div class="signature-name">Mohammad Saeedul Mostafa</div>
                <div class="signature-title">Academic Principal</div>
              </div>
              <div class="signature-block">
                <div class="signature-line"></div>
                <div class="signature-name">Ahmadullah AL Jami</div>
                <div class="signature-title">Academic Director</div>
              </div>
              <div class="signature-block">
                <div class="signature-line"></div>
                <div class="signature-name">Mohammad Firoz Al Unaid</div>
                <div class="signature-title">Administrator</div>
              </div>
            </div>
            
            <!-- Certificate details -->
            <div class="certificate-details">
              <div class="detail-item">
                <div class="detail-label">Certificate No.</div>
                <div class="detail-value">{{ certificate.certificate_number }}</div>
              </div>
              <div class="detail-item">
                <div class="detail-label">Verification Code</div>
                <div class="detail-value">{{ certificate.verification_code }}</div>
              </div>
              <div class="detail-item">
                <div class="detail-label">Issue Date</div>
                <div class="detail-value">{{ certificate.issue_date }}</div>
              </div>
            </div>
            
            <!-- Seal placeholder -->
            <div class="seal-section">
              <div class="seal">
                <div class="seal-text">IQRA</div>
                <div class="seal-year">EST. 2020</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Action buttons (not printed) -->
      <div class="actions-section no-print">
        <button @click="printCertificate" class="btn-primary">
          <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
          </svg>
          Print Certificate
        </button>
        
        <button @click="downloadCertificate" class="btn-success">
          <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
          Download PDF
        </button>
        
        <button @click="shareCertificate" class="btn-secondary">
          <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
          </svg>
          Share
        </button>
        
        <button @click="verifyAnother" class="btn-outline">
          <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          Verify Another
        </button>
      </div>
    </div>
    
    <!-- Share Modal -->
    <div v-if="showShareModal" class="modal-overlay" @click.self="showShareModal = false">
      <div class="modal-content">
        <h3 class="modal-title">Share Certificate</h3>
        <p class="modal-text">Copy the certificate link to share:</p>
        <div class="share-link-container">
          <input
            ref="shareLink"
            :value="shareUrl"
            readonly
            class="share-link-input"
          />
          <button @click="copyLink" class="copy-btn">Copy</button>
        </div>
        <button @click="showShareModal = false" class="close-btn">Close</button>
      </div>
    </div>
  </FrontendLayout>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import FrontendLayout from '@/layouts/FrontendLayout.vue'

const props = defineProps({
  certificate: Object,
})

const showShareModal = ref(false)
const shareUrl = ref(window.location.href)
const shareLink = ref(null)

const formatDate = (dateString) => {
  const date = new Date(dateString)
  const day = date.getDate()
  const month = date.toLocaleDateString('en-US', { month: 'long' })
  const year = date.getFullYear()
  
  // Add ordinal suffix
  const ordinal = (day) => {
    const s = ['th', 'st', 'nd', 'rd']
    const v = day % 100
    return day + (s[(v - 20) % 10] || s[v] || s[0])
  }
  
  return `${ordinal(day)} day of ${month}, ${year}`
}

const printCertificate = () => {
  window.print()
}

const downloadCertificate = () => {
  window.location.href = route('certificates.download.public', props.certificate.verification_code)
}

const shareCertificate = () => {
  showShareModal.value = true
}

const verifyAnother = () => {
  router.visit(route('certificates.verify'))
}

const copyLink = async () => {
  try {
    await navigator.clipboard.writeText(shareUrl.value)
    alert('Link copied to clipboard!')
  } catch (err) {
    shareLink.value?.select()
    document.execCommand('copy')
    alert('Link copied to clipboard!')
  }
}
</script>

<style scoped>
/* Page container */
.certificate-page {
  min-height: 100vh;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  padding: 2rem 1rem;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

/* Certificate container - A4 Landscape */
.certificate-container {
  width: 297mm;
  height: 210mm;
  background: radial-gradient(ellipse at center, #ffffff 0%, #faf9f7 100%);
  position: relative;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
  overflow: hidden;
  margin-bottom: 2rem;
}

/* Multi-layer borders */
.border-frame {
  position: absolute;
  top: 5mm;
  left: 5mm;
  right: 5mm;
  bottom: 5mm;
  border: 3px solid #8B7355;
  pointer-events: none;
}

.inner-border {
  position: absolute;
  top: 8mm;
  left: 8mm;
  right: 8mm;
  bottom: 8mm;
  border: 1px solid #C4A57B;
  pointer-events: none;
}

.decorative-border {
  position: absolute;
  top: 10mm;
  left: 10mm;
  right: 10mm;
  bottom: 10mm;
  border: 1px dotted #8B7355;
  pointer-events: none;
}

/* Corner flourishes */
.corner-flourish {
  position: absolute;
  width: 30mm;
  height: 30mm;
  border: 2px solid #C4A57B;
  pointer-events: none;
}

.corner-flourish.top-left {
  top: 12mm;
  left: 12mm;
  border-right: none;
  border-bottom: none;
}

.corner-flourish.top-right {
  top: 12mm;
  right: 12mm;
  border-left: none;
  border-bottom: none;
}

.corner-flourish.bottom-left {
  bottom: 12mm;
  left: 12mm;
  border-right: none;
  border-top: none;
}

.corner-flourish.bottom-right {
  bottom: 12mm;
  right: 12mm;
  border-left: none;
  border-top: none;
}

/* Content area */
.certificate-content {
  position: relative;
  padding: 20mm 25mm;
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  z-index: 1;
}

/* Header section */
.header-section {
  text-align: center;
  margin-bottom: 8mm;
}

.islamic-pattern {
  color: #C4A57B;
  font-size: 20px;
  margin-bottom: 5mm;
  letter-spacing: 10px;
}

.academy-name {
  font-size: 32px;
  font-weight: bold;
  color: #2d5a27;
  margin-bottom: 2mm;
  font-family: 'Georgia', serif;
}

.academy-english {
  font-size: 18px;
  color: #8B7355;
  letter-spacing: 3px;
  margin-bottom: 2mm;
}

.tagline {
  font-size: 12px;
  color: #8B7355;
  font-style: italic;
  letter-spacing: 1px;
}

/* Ornamental dividers */
.ornamental-divider {
  text-align: center;
  color: #C4A57B;
  font-size: 16px;
  margin: 5mm 0;
  letter-spacing: 8px;
}

/* Certificate title */
.certificate-title {
  text-align: center;
  margin: 8mm 0;
}

.arabic-text {
  font-size: 24px;
  color: #8B7355;
  margin-bottom: 3mm;
  font-family: 'Traditional Arabic', 'Arial', sans-serif;
}

.main-title {
  font-size: 36px;
  font-weight: bold;
  color: #2c2c2c;
  font-family: 'Georgia', serif;
  text-transform: uppercase;
  letter-spacing: 2px;
  margin-bottom: 3mm;
}

.bengali-title {
  font-size: 20px;
  color: #8B7355;
}

/* Recipient section */
.recipient-section {
  text-align: center;
  margin: 10mm 0;
}

.presented-text {
  font-size: 16px;
  color: #666;
  margin-bottom: 5mm;
  font-style: italic;
}

.student-name {
  font-size: 32px;
  font-weight: bold;
  color: #2d5a27;
  border-bottom: 2px solid #C4A57B;
  display: inline-block;
  padding: 0 10mm 2mm;
  margin-bottom: 5mm;
  font-family: 'Georgia', serif;
}

.achievement-text {
  font-size: 16px;
  color: #666;
  font-style: italic;
}

/* Course section */
.course-section {
  text-align: center;
  margin: 8mm 0;
}

.course-title {
  font-size: 24px;
  font-weight: bold;
  color: #8B7355;
  margin-bottom: 3mm;
  font-family: 'Georgia', serif;
}

.course-description {
  font-size: 14px;
  color: #666;
  line-height: 1.6;
  max-width: 80%;
  margin: 0 auto 5mm;
  text-align: justify;
}

.score-section {
  margin: 5mm 0;
  font-size: 18px;
}

.score-label {
  color: #666;
  margin-right: 5mm;
}

.score-value {
  color: #2d5a27;
  font-weight: bold;
  font-size: 24px;
}

.instructors-section {
  margin: 5mm 0;
}

.instructors-label {
  font-size: 14px;
  color: #666;
  font-style: italic;
  margin-bottom: 2mm;
}

.instructors-list {
  font-size: 16px;
  color: #8B7355;
}

.instructor-name {
  font-weight: 500;
}

.date-section {
  font-size: 14px;
  color: #666;
  font-style: italic;
  margin-top: 5mm;
}

.date-value {
  font-weight: bold;
  color: #8B7355;
}

/* Footer section */
.footer-section {
  position: relative;
  margin-top: auto;
}

/* Signatures */
.signatures-container {
  display: flex;
  justify-content: space-around;
  margin-bottom: 8mm;
  padding: 0 20mm;
}

.signature-block {
  text-align: center;
  min-width: 60mm;
}

.signature-line {
  width: 50mm;
  height: 1px;
  background: #8B7355;
  margin: 0 auto 2mm;
}

.signature-name {
  font-size: 14px;
  font-weight: bold;
  color: #2c2c2c;
  margin-bottom: 1mm;
}

.signature-title {
  font-size: 12px;
  color: #666;
  font-style: italic;
}

/* Certificate details */
.certificate-details {
  display: flex;
  justify-content: space-around;
  padding: 5mm 20mm;
  border-top: 1px solid #C4A57B;
  margin-top: 5mm;
}

.detail-item {
  text-align: center;
}

.detail-label {
  font-size: 11px;
  color: #999;
  text-transform: uppercase;
  letter-spacing: 1px;
  margin-bottom: 1mm;
}

.detail-value {
  font-size: 13px;
  font-weight: bold;
  color: #2c2c2c;
}

/* Seal */
.seal-section {
  position: absolute;
  bottom: 15mm;
  right: 25mm;
}

.seal {
  width: 25mm;
  height: 25mm;
  border: 3px double #C4A57B;
  border-radius: 50%;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  background: rgba(255, 255, 255, 0.9);
}

.seal-text {
  font-size: 12px;
  font-weight: bold;
  color: #8B7355;
  letter-spacing: 2px;
}

.seal-year {
  font-size: 10px;
  color: #999;
}

/* Action buttons */
.actions-section {
  display: flex;
  gap: 1rem;
  justify-content: center;
  flex-wrap: wrap;
}

.btn-primary,
.btn-success,
.btn-secondary,
.btn-outline {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1.5rem;
  border-radius: 0.5rem;
  font-weight: 500;
  transition: all 0.2s;
  border: none;
  cursor: pointer;
  font-size: 16px;
}

.btn-primary {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
}

.btn-success {
  background: linear-gradient(135deg, #2d5a27 0%, #3d7a37 100%);
  color: white;
}

.btn-success:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 20px rgba(45, 90, 39, 0.4);
}

.btn-secondary {
  background: linear-gradient(135deg, #8B7355 0%, #C4A57B 100%);
  color: white;
}

.btn-secondary:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 20px rgba(139, 115, 85, 0.4);
}

.btn-outline {
  background: white;
  color: #8B7355;
  border: 2px solid #8B7355;
}

.btn-outline:hover {
  background: #8B7355;
  color: white;
}

.icon {
  width: 20px;
  height: 20px;
}

/* Modal */
.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 9999;
}

.modal-content {
  background: white;
  padding: 2rem;
  border-radius: 1rem;
  max-width: 500px;
  width: 90%;
}

.modal-title {
  font-size: 1.5rem;
  font-weight: bold;
  margin-bottom: 1rem;
}

.modal-text {
  color: #666;
  margin-bottom: 1rem;
}

.share-link-container {
  display: flex;
  gap: 0.5rem;
  margin-bottom: 1rem;
}

.share-link-input {
  flex: 1;
  padding: 0.5rem;
  border: 1px solid #ddd;
  border-radius: 0.25rem;
  font-size: 14px;
}

.copy-btn {
  padding: 0.5rem 1rem;
  background: #667eea;
  color: white;
  border: none;
  border-radius: 0.25rem;
  cursor: pointer;
}

.copy-btn:hover {
  background: #5a67d8;
}

.close-btn {
  width: 100%;
  padding: 0.5rem;
  background: #f3f4f6;
  border: 1px solid #ddd;
  border-radius: 0.25rem;
  cursor: pointer;
}

.close-btn:hover {
  background: #e5e7eb;
}

/* Print styles */
@media print {
  body {
    margin: 0;
    padding: 0;
  }
  
  .certificate-page {
    background: white;
    padding: 0;
    min-height: auto;
  }
  
  .certificate-container {
    box-shadow: none;
    margin: 0;
    page-break-after: avoid;
    page-break-inside: avoid;
  }
  
  .actions-section,
  .no-print {
    display: none !important;
  }
}

/* Responsive adjustments */
@media screen and (max-width: 1200px) {
  .certificate-container {
    transform: scale(0.8);
    transform-origin: center;
  }
}

@media screen and (max-width: 768px) {
  .certificate-container {
    transform: scale(0.5);
    transform-origin: center;
  }
  
  .actions-section {
    flex-direction: column;
    width: 100%;
    max-width: 300px;
  }
  
  .btn-primary,
  .btn-success,
  .btn-secondary,
  .btn-outline {
    width: 100%;
  }
}
</style>