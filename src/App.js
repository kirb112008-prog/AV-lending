import { BrowserRouter, Outlet, Route, Routes } from 'react-router';

import Header from "./components/Header"
import Hero from "./components/Hero"
import Presentation from "./components/Presentation"
import Mistakes from "./components/Mistakes"
import Reason from "./components/Reason"
import Connection from "./components/Connection"
import Integrations from "./components/Integrations"
import Details from "./components/Details"
import Calculator from "./components/Calculator"
import FAQ from "./components/FAQ"
import CTAForm from "./components/CTAForm"
import Tarifs from "./components/Tarifs"
import Footer from "./components/Footer"
import Reveal from "./components/Reveal"
import StickyDemo from "./components/StickyDemo"
import PrivacyPage from "./components/PrivacyPage"
import QRPage from "./components/QRPage"

function SiteLayout() {
  return (
    <>
      <Header />
      <Outlet />
      <Footer />
    </>
  )
}

function Home() {
  return (
    <>
      <Hero />
      <Reveal><Presentation /></Reveal>
      <Reveal><Mistakes /></Reveal>
      <Reveal><Reason /></Reveal>
      <Reveal><Connection /></Reveal>
      <Reveal><Integrations /></Reveal>
      <Reveal><Details /></Reveal>
      <Reveal><Calculator /></Reveal>
      <Reveal><FAQ /></Reveal>
      <Reveal><CTAForm /></Reveal>
      <Reveal><Tarifs /></Reveal>
      <StickyDemo />
    </>
  )
}

function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/qr" element={<QRPage />} />
        <Route element={<SiteLayout />}>
          <Route path="/" element={<Home />} />
          <Route path="/privacy" element={<PrivacyPage />} />
          <Route path="*" element={<PrivacyPage />} />
        </Route>
      </Routes>
    </BrowserRouter>
  );
}

export default App;
