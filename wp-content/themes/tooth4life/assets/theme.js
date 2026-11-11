document.documentElement.classList.add("js");

document.querySelectorAll(".t4l-has-sub").forEach((item) => {
  const button = item.querySelector(".t4l-sub-toggle");
  if (!button) return;
  const setOpen = (open) => button.setAttribute("aria-expanded", open ? "true" : "false");
  item.addEventListener("mouseenter", () => setOpen(true));
  item.addEventListener("mouseleave", () => setOpen(false));
  item.addEventListener("focusin", () => setOpen(true));
  item.addEventListener("focusout", (event) => {
    if (!item.contains(event.relatedTarget)) setOpen(false);
  });
});

document.querySelectorAll(".t4l-menu").forEach((menu) => {
  const close = () => menu.removeAttribute("open");
  menu.querySelectorAll("a").forEach((link) => {
    link.addEventListener("click", close);
  });
  menu.parentElement?.querySelector(".t4l-pill")?.addEventListener("click", close);
});

document.querySelectorAll(".t4l-showcase").forEach((root) => {
  const links = [...root.querySelectorAll(".t4l-film-link")];
  const panels = [...root.querySelectorAll(".t4l-panel")];

  const selected = () => {
    const id = location.hash.replace("#", "");
    const match = panels.find((panel) => panel.id === id);
    return match || panels[0] || null;
  };

  const sync = () => {
    const current = selected();
    panels.forEach((panel) => {
      if (panel !== current) {
        panel.querySelectorAll("video").forEach((video) => video.pause());
      }
    });
    links.forEach((link) => {
      const on = current !== null && link.getAttribute("href") === "#" + current.id;
      if (on) {
        link.setAttribute("aria-current", "true");
      } else {
        link.removeAttribute("aria-current");
      }
    });
  };

  window.addEventListener("hashchange", sync);
  sync();
});

document.querySelectorAll("[data-slider]").forEach((root) => {
  const slides = [...root.querySelectorAll("[data-slide]")];
  const dots = [...root.querySelectorAll("[data-dot]")];
  let index = 0;

  const show = (next) => {
    index = (next + slides.length) % slides.length;
    slides.forEach((slide, item) => {
      slide.hidden = item !== index;
    });
    dots.forEach((dot, item) => {
      if (item === index) {
        dot.setAttribute("aria-current", "true");
      } else {
        dot.removeAttribute("aria-current");
      }
    });
  };

  root.querySelector("[data-next]")?.addEventListener("click", () => show(index + 1));
  root.querySelector("[data-prev]")?.addEventListener("click", () => show(index - 1));
  dots.forEach((dot, item) => {
    dot.addEventListener("click", () => show(item));
  });
});
