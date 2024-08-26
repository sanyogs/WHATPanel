<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Hosting Bill | Madpopo</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="../assets/css/main.css" />
    <link
      rel="stylesheet"
      type="text/css"
      href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css"
    />
    <link
      rel="stylesheet"
      type="text/css"
      href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css"
    />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
  </head>
  <body>
    <!-- Topbar Start -->
    <section id="topbarSection" class="innerHeader">
      <div class="topbarWrap">
        <div class="col-md-6 col-lg-6 col-12">
          <div class="leftSide">
            <ul>
              <li>
                <a href="mailto:noreply@whatpanel.com">
                  <span
                    ><svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="14"
                      height="13"
                      viewBox="0 0 14 13"
                      fill="none"
                    >
                      <path
                        d="M13.9732 2.6495L9.06267 7.55999C8.51513 8.10614 7.77335 8.41285 7 8.41285C6.22665 8.41285 5.48487 8.10614 4.93733 7.55999L0.0268333 2.6495C0.0186667 2.74166 0 2.82508 0 2.91666V9.91666C0.00092625 10.6899 0.308514 11.4313 0.855295 11.978C1.40208 12.5248 2.1434 12.8324 2.91667 12.8333H11.0833C11.8566 12.8324 12.5979 12.5248 13.1447 11.978C13.6915 11.4313 13.9991 10.6899 14 9.91666V2.91666C14 2.82508 13.9813 2.74166 13.9732 2.6495Z"
                        fill="#D1D7E2"
                      />
                      <path
                        d="M8.23743 6.73517L13.5656 1.40642C13.3075 0.978436 12.9434 0.624188 12.5086 0.377843C12.0737 0.131497 11.5827 0.00136539 11.0829 0H2.91626C2.41647 0.00136539 1.92547 0.131497 1.49061 0.377843C1.05575 0.624188 0.691706 0.978436 0.433594 1.40642L5.76176 6.73517C6.09051 7.0626 6.5356 7.24644 6.99959 7.24644C7.46359 7.24644 7.90868 7.0626 8.23743 6.73517Z"
                        fill="#D1D7E2"
                      />
                    </svg>
                  </span>
                  noreply@whatpanel.com
                </a>
              </li>
              <li>
                <a href="tel:+91 7075335566">
                  <span>
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="14"
                      height="14"
                      viewBox="0 0 14 14"
                      fill="none"
                    >
                      <path
                        d="M7 0C3.14008 0 0 3.14008 0 7C0 10.8599 3.14008 14 7 14C10.8599 14 14 10.8599 14 7C14 3.14008 10.8599 0 7 0ZM10.3046 9.67575L10.0001 10.0258C9.67867 10.3477 9.25517 10.5 8.83342 10.5C6.66692 10.5 3.5 7.49992 3.5 5.16658C3.5 4.74483 3.65225 4.32133 3.97425 3.99992L4.32425 3.69542C4.58442 3.43525 5.00675 3.43525 5.26692 3.69542L5.85025 4.45492C6.11042 4.71508 6.11042 5.13742 5.85025 5.39758L5.35383 6.02117C5.87942 7.33017 6.77075 8.18533 7.97883 8.64617L8.60242 8.14975C8.86258 7.88958 9.28492 7.88958 9.54508 8.14975L10.3046 8.73308C10.5647 8.99325 10.5647 9.41558 10.3046 9.67575Z"
                        fill="#D1D7E2"
                      />
                    </svg>
                  </span>
                  + 255 545 11222
                </a>
              </li>
            </ul>
          </div>
        </div>

        <div class="col-md-6 col-lg-6 col-12">
          <div class="selectCountryWrap">
			<?php
			  
			  $db = \Config\Database::connect();
			  
			  $languagess = $db->table('hd_languages')->where('active', 1)->get()->getResult();
			  
			  ?>
			  <select name="languages" id="languages" class="common-select">
				  <options value="">--Select Language--</options>
				  
				  <?php
				  	foreach($languagess as $language) { ?>
				  <options value="<?= $language->name; ?>"><?= ucfirst($language->name); ?></options>
				  <?php } ?>
			  </select>
			</div>
          <div class="rightSide">
            <ul>
              <li>
                <a href="#" target="_blank" rel="noopener noreferrer">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="12"
                    height="13"
                    viewBox="0 0 12 13"
                    fill="none"
                  >
                    <path
                      fill-rule="evenodd"
                      clip-rule="evenodd"
                      d="M0.797608 1.7564C0.797608 1.50208 0.895152 1.25818 1.06878 1.07835C1.24241 0.898515 1.47791 0.797487 1.72346 0.797487H10.9527C11.0744 0.797281 11.195 0.821936 11.3074 0.87004C11.4199 0.918144 11.5221 0.988753 11.6082 1.07782C11.6943 1.16689 11.7626 1.27268 11.8092 1.38912C11.8557 1.50556 11.8797 1.63037 11.8796 1.7564V11.3153C11.8797 11.4414 11.8559 11.5662 11.8094 11.6827C11.7629 11.7992 11.6946 11.9051 11.6086 11.9942C11.5226 12.0834 11.4204 12.1541 11.3079 12.2023C11.1955 12.2505 11.075 12.2753 10.9532 12.2753H1.72346C1.60183 12.2753 1.4814 12.2504 1.36903 12.2022C1.25667 12.154 1.15458 12.0833 1.0686 11.9942C0.982625 11.9051 0.914438 11.7993 0.867939 11.6829C0.82144 11.5665 0.797541 11.4418 0.797608 11.3158V1.7564ZM5.18406 5.17365H6.68466V5.95414C6.90127 5.50546 7.45537 5.10165 8.28803 5.10165C9.88434 5.10165 10.2626 5.99535 10.2626 7.63511V10.6725H8.64718V8.00866C8.64718 7.07479 8.43058 6.54785 7.88051 6.54785C7.11736 6.54785 6.80002 7.116 6.80002 8.00866V10.6725H5.18406V5.17365ZM2.41356 10.6011H4.02952V5.10165H2.41356V10.6005V10.6011ZM4.26073 3.30799C4.26378 3.45129 4.23916 3.59378 4.18831 3.72709C4.13747 3.8604 4.06143 3.98186 3.96466 4.08432C3.86788 4.18678 3.75232 4.26819 3.62475 4.32378C3.49719 4.37936 3.36019 4.40799 3.22179 4.40799C3.0834 4.40799 2.9464 4.37936 2.81883 4.32378C2.69127 4.26819 2.57571 4.18678 2.47893 4.08432C2.38216 3.98186 2.30612 3.8604 2.25527 3.72709C2.20443 3.59378 2.17981 3.45129 2.18286 3.30799C2.18884 3.02671 2.30092 2.75903 2.49511 2.56229C2.6893 2.36555 2.95015 2.25538 3.22179 2.25538C3.49344 2.25538 3.75429 2.36555 3.94848 2.56229C4.14266 2.75903 4.25475 3.02671 4.26073 3.30799Z"
                      fill="white"
                    />
                  </svg>
                </a>
              </li>
              <li>
                <a href="#" target="_blank" rel="noopener noreferrer">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="15"
                    height="13"
                    viewBox="0 0 15 13"
                    fill="none"
                  >
                    <path
                      d="M14.525 2.14134C14.0125 2.36843 13.462 2.52187 12.8832 2.59123C13.4804 2.23389 13.9272 1.67148 14.1402 1.00894C13.5791 1.34221 12.965 1.57679 12.3247 1.70249C11.894 1.2427 11.3237 0.937942 10.7021 0.835534C10.0805 0.733126 9.44249 0.838798 8.88712 1.13614C8.33175 1.43349 7.89008 1.90587 7.63069 2.47995C7.3713 3.05403 7.3087 3.69769 7.4526 4.311C6.31572 4.25391 5.20354 3.95842 4.18825 3.44369C3.17295 2.92896 2.27724 2.20649 1.55923 1.32319C1.31372 1.74668 1.17256 2.2377 1.17256 2.76062C1.17228 3.23138 1.28821 3.69492 1.51005 4.11013C1.7319 4.52534 2.0528 4.87937 2.44428 5.14081C1.99026 5.12636 1.54626 5.00368 1.14923 4.78298V4.81981C1.14919 5.48006 1.37758 6.11999 1.79564 6.63103C2.21371 7.14206 2.7957 7.49271 3.44287 7.62349C3.0217 7.73747 2.58013 7.75426 2.15151 7.67259C2.33411 8.2407 2.68978 8.73749 3.16875 9.09341C3.64771 9.44933 4.22599 9.64657 4.82262 9.6575C3.8098 10.4526 2.55898 10.8839 1.27137 10.882C1.04329 10.882 0.815393 10.8687 0.588867 10.8421C1.89587 11.6824 3.41731 12.1284 4.97115 12.1267C10.2311 12.1267 13.1066 7.77018 13.1066 3.99184C13.1066 3.86908 13.1035 3.7451 13.098 3.62235C13.6573 3.21786 14.1401 2.71698 14.5238 2.14318L14.525 2.14134Z"
                      fill="white"
                    />
                  </svg>
                </a>
              </li>
              <li>
                <a href="#" target="_blank" rel="noopener noreferrer">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="9"
                    height="16"
                    viewBox="0 0 9 16"
                    fill="none"
                  >
                    <path
                      d="M7.64206 8.88122L8.03987 6.279H5.5532V4.59033C5.5532 3.87855 5.90045 3.18429 7.01424 3.18429H8.14458V0.968972C8.14458 0.968972 7.11895 0.793274 6.13806 0.793274C4.09037 0.793274 2.75194 2.03979 2.75194 4.296V6.27945H0.475586V8.88167H2.75194V15.1726H5.5532V8.88167L7.64206 8.88122Z"
                      fill="white"
                    />
                  </svg>
                </a>
              </li>
            </ul>
            <div class="sinupBtnWrap">
              <a href="#" target="_blank" rel="noopener noreferrer">
                <span>
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="8"
                    height="12"
                    viewBox="0 0 8 12"
                    fill="none"
                  >
                    <path
                      d="M3.57091 6.7166C1.52894 6.90118 -0.02676 8.6266 0.000348915 10.6767V10.7807C0.000348915 11.1776 0.32213 11.4994 0.719061 11.4994C1.11599 11.4994 1.43777 11.1776 1.43777 10.7807V10.648C1.41617 9.3892 2.3451 8.31578 3.59391 8.15643C4.91214 8.02571 6.08677 8.98839 6.21748 10.3066C6.22521 10.3846 6.22912 10.4628 6.22918 10.5411V10.7807C6.22918 11.1776 6.55096 11.4994 6.9479 11.4994C7.34483 11.4994 7.66661 11.1776 7.66661 10.7807V10.5411C7.66427 8.42173 5.94428 6.70555 3.82493 6.70789C3.74019 6.708 3.65547 6.7109 3.57091 6.7166Z"
                      fill="white"
                    />
                    <path
                      d="M3.83286 5.7497C5.42058 5.7497 6.70771 4.46258 6.70771 2.87485C6.70771 1.28712 5.42058 0 3.83286 0C2.24513 0 0.958008 1.28712 0.958008 2.87485C0.95958 4.46192 2.24578 5.74811 3.83286 5.7497ZM3.83286 1.43742C4.62672 1.43742 5.27028 2.08099 5.27028 2.87485C5.27028 3.66871 4.62672 4.31227 3.83286 4.31227C3.03899 4.31227 2.39543 3.66871 2.39543 2.87485C2.39543 2.08099 3.03899 1.43742 3.83286 1.43742Z"
                      fill="white"
                    />
                  </svg>
                  <span class="btnText">Sign Up</span>
                </span>
              </a>
            </div>
          </div>
        </div>
      </div>
      <!-- Navbar Start -->
      <header id="headerSection" class="header">
        <nav class="navbar navbar-expand-lg">
          <div class="container-fluid px-0">
            <a class="navbar-brand" href="#">
              <span>
                <img
                  src="./assets/images/header/madpopo-dark-logo.svg"
                  alt=""
                  width="135"
                  height="77"
                  class="img-fluid"
                />
              </span>
            </a>
            <button
              class="navbar-toggler collapsed"
              type="button"
              data-bs-toggle="collapse"
              data-bs-target="#navbarSupportedContent"
              aria-controls="navbarSupportedContent"
              aria-expanded="false"
              aria-label="Toggle navigation"
              onclick="addHeightToHeader()"
            >
              <span class="hamburgerIcon">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="16"
                  height="12"
                  viewBox="0 0 16 12"
                  fill="none"
                >
                  <path
                    d="M15 5.22217H1C0.447719 5.22217 0 5.66989 0 6.22217C0 6.77445 0.447719 7.22217 1 7.22217H15C15.5523 7.22217 16 6.77445 16 6.22217C16 5.66989 15.5523 5.22217 15 5.22217Z"
                    fill="white"
                  />
                  <path
                    d="M1 2.55566H15C15.5523 2.55566 16 2.10795 16 1.55566C16 1.00338 15.5523 0.555664 15 0.555664H1C0.447719 0.555664 0 1.00338 0 1.55566C0 2.10795 0.447719 2.55566 1 2.55566Z"
                    fill="white"
                  />
                  <path
                    d="M15 9.88916H1C0.447719 9.88916 0 10.3369 0 10.8892C0 11.4414 0.447719 11.8892 1 11.8892H15C15.5523 11.8892 16 11.4414 16 10.8892C16 10.3369 15.5523 9.88916 15 9.88916Z"
                    fill="white"
                  />
                </svg>
              </span>
              <span class="closeIcon"
                ><svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="16"
                  height="12"
                  viewBox="0 0 6 6"
                  fill="none"
                >
                  <path
                    d="M3.94267 2.99984L5.47133 1.47117C5.732 1.21051 5.732 0.789174 5.47133 0.528508C5.21067 0.267841 4.78934 0.267841 4.52867 0.528508L3 2.05717L1.47133 0.528508C1.21067 0.267841 0.789335 0.267841 0.528668 0.528508C0.268001 0.789174 0.268001 1.21051 0.528668 1.47117L2.05734 2.99984L0.528668 4.52851C0.268001 4.78918 0.268001 5.21051 0.528668 5.47117C0.658668 5.60117 0.829334 5.66651 1 5.66651C1.17067 5.66651 1.34133 5.60117 1.47133 5.47117L3 3.94251L4.52867 5.47117C4.65867 5.60117 4.82933 5.66651 5 5.66651C5.17067 5.66651 5.34133 5.60117 5.47133 5.47117C5.732 5.21051 5.732 4.78918 5.47133 4.52851L3.94267 2.99984Z"
                    fill="white"
                  />
                </svg>
              </span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                  <a
                    class="nav-link dropdown-toggle"
                    href="#"
                    id="navbarDropdown"
                    role="button"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                  >
                    Hosting
                    <span>
                      <img
                        src="./assets/images/header/down-arrow-white.png"
                        alt=""
                        class="img-fluid"
                        width="10"
                        height="10"
                      />
                    </span>
                  </a>
                  <div class="megaMenus dropdown-menu">
                    <ul class="dropMenuItmList">
                      <li class="dropMenuItems">
                        <a href="">
                          <div class="dropItemWrap">
                            <p class="menuName">cPanel Hosting</p>
                            <span class="menuDesc"
                              >All our hosting accounts allow you to install
                              popular software such</span
                            >
                          </div>
                        </a>
                      </li>
                      <li class="dropMenuItems">
                        <a href="">
                          <div class="dropItemWrap">
                            <p class="menuName">Direct Admin Hosting</p>
                            <span class="menuDesc"
                              >All our hosting accounts allow you to install
                              popular software such</span
                            >
                          </div>
                        </a>
                      </li>
                      <li class="dropMenuItems">
                        <a href="">
                          <div class="dropItemWrap">
                            <p class="menuName">Plesk Hosting</p>
                            <span class="menuDesc"
                              >All our hosting accounts allow you to install
                              popular software such</span
                            >
                          </div>
                        </a>
                      </li>
                      <li class="dropMenuItems">
                        <a href="">
                          <div class="dropItemWrap">
                            <p class="menuName">Virtualmin hosting</p>
                            <span class="menuDesc"
                              >All our hosting accounts allow you to install
                              popular software such</span
                            >
                          </div>
                        </a>
                      </li>
                      <li class="dropMenuItems">
                        <a href="">
                          <div class="dropItemWrap">
                            <p class="menuName">Virtualmin hosting</p>
                            <span class="menuDesc"
                              >All our hosting accounts allow you to install
                              popular software such</span
                            >
                          </div>
                        </a>
                      </li>
                      <li class="dropMenuItems">
                        <a href="">
                          <div class="dropItemWrap">
                            <p class="menuName">Virtualmin hosting</p>
                            <span class="menuDesc"
                              >All our hosting accounts allow you to install
                              popular software such</span
                            >
                          </div>
                        </a>
                      </li>
                      <li class="dropMenuItems">
                        <a href="">
                          <div class="dropItemWrap">
                            <p class="menuName">Virtualmin hosting</p>
                            <span class="menuDesc"
                              >All our hosting accounts allow you to install
                              popular software such</span
                            >
                          </div>
                        </a>
                      </li>
                      <li class="dropMenuItems">
                        <a href="">
                          <div class="dropItemWrap">
                            <p class="menuName">Virtualmin hosting</p>
                            <span class="menuDesc"
                              >All our hosting accounts allow you to install
                              popular software such</span
                            >
                          </div>
                        </a>
                      </li>
                      <li class="dropMenuItems">
                        <a href="">
                          <div class="dropItemWrap">
                            <p class="menuName">Virtualmin hosting</p>
                            <span class="menuDesc"
                              >All our hosting accounts allow you to install
                              popular software such</span
                            >
                          </div>
                        </a>
                      </li>
                    </ul>
                  </div>
                </li>
                <li class="nav-item dropdown">
                  <a
                    class="nav-link dropdown-toggle"
                    href="#"
                    id="navbarDropdown"
                    role="button"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                  >
                    Domains
                    <span>
                      <img
                        src="./assets/images/header/down-arrow-white.png"
                        alt=""
                        class="img-fluid"
                        width="10"
                        height="10"
                      />
                    </span>
                  </a>
                  <div class="megaMenus dropdown-menu">
                    <ul class="dropMenuItmList">
                      <li class="dropMenuItems">
                        <a href="">
                          <div class="dropItemWrap">
                            <p class="menuName">cPanel Hosting</p>
                            <span class="menuDesc"
                              >All our hosting accounts allow you to install
                              popular software such</span
                            >
                          </div>
                        </a>
                      </li>
                      <li class="dropMenuItems">
                        <a href="">
                          <div class="dropItemWrap">
                            <p class="menuName">Direct Admin Hosting</p>
                            <span class="menuDesc"
                              >All our hosting accounts allow you to install
                              popular software such</span
                            >
                          </div>
                        </a>
                      </li>
                      <li class="dropMenuItems">
                        <a href="">
                          <div class="dropItemWrap">
                            <p class="menuName">Plesk Hosting</p>
                            <span class="menuDesc"
                              >All our hosting accounts allow you to install
                              popular software such</span
                            >
                          </div>
                        </a>
                      </li>
                      <li class="dropMenuItems">
                        <a href="">
                          <div class="dropItemWrap">
                            <p class="menuName">Virtualmin hosting</p>
                            <span class="menuDesc"
                              >All our hosting accounts allow you to install
                              popular software such</span
                            >
                          </div>
                        </a>
                      </li>
                      <li class="dropMenuItems">
                        <a href="">
                          <div class="dropItemWrap">
                            <p class="menuName">Virtualmin hosting</p>
                            <span class="menuDesc"
                              >All our hosting accounts allow you to install
                              popular software such</span
                            >
                          </div>
                        </a>
                      </li>
                      <li class="dropMenuItems">
                        <a href="">
                          <div class="dropItemWrap">
                            <p class="menuName">Virtualmin hosting</p>
                            <span class="menuDesc"
                              >All our hosting accounts allow you to install
                              popular software such</span
                            >
                          </div>
                        </a>
                      </li>
                      <li class="dropMenuItems">
                        <a href="">
                          <div class="dropItemWrap">
                            <p class="menuName">Virtualmin hosting</p>
                            <span class="menuDesc"
                              >All our hosting accounts allow you to install
                              popular software such</span
                            >
                          </div>
                        </a>
                      </li>
                      <li class="dropMenuItems">
                        <a href="">
                          <div class="dropItemWrap">
                            <p class="menuName">Virtualmin hosting</p>
                            <span class="menuDesc"
                              >All our hosting accounts allow you to install
                              popular software such</span
                            >
                          </div>
                        </a>
                      </li>
                      <li class="dropMenuItems">
                        <a href="">
                          <div class="dropItemWrap">
                            <p class="menuName">Virtualmin hosting</p>
                            <span class="menuDesc"
                              >All our hosting accounts allow you to install
                              popular software such</span
                            >
                          </div>
                        </a>
                      </li>
                    </ul>
                  </div>
                </li>
                <li class="nav-item">
                  <a class="nav-link" aria-current="page" href="#">Affiliate</a>
                </li>
                <li class="nav-item dropdown">
                  <a
                    class="nav-link dropdown-toggle"
                    href="#"
                    id="navbarDropdown"
                    role="button"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                  >
                    About Us
                    <span>
                      <img
                        src="./assets/images/header/down-arrow-white.png"
                        alt=""
                        class="img-fluid"
                        width="10"
                        height="10"
                      />
                    </span>
                  </a>
                  <div class="megaMenus dropdown-menu">
                    <ul class="dropMenuItmList">
                      <li class="dropMenuItems">
                        <a href="">
                          <div class="dropItemWrap">
                            <p class="menuName">cPanel Hosting</p>
                            <span class="menuDesc"
                              >All our hosting accounts allow you to install
                              popular software such</span
                            >
                          </div>
                        </a>
                      </li>
                      <li class="dropMenuItems">
                        <a href="">
                          <div class="dropItemWrap">
                            <p class="menuName">Direct Admin Hosting</p>
                            <span class="menuDesc"
                              >All our hosting accounts allow you to install
                              popular software such</span
                            >
                          </div>
                        </a>
                      </li>
                      <li class="dropMenuItems">
                        <a href="">
                          <div class="dropItemWrap">
                            <p class="menuName">Plesk Hosting</p>
                            <span class="menuDesc"
                              >All our hosting accounts allow you to install
                              popular software such</span
                            >
                          </div>
                        </a>
                      </li>
                      <li class="dropMenuItems">
                        <a href="">
                          <div class="dropItemWrap">
                            <p class="menuName">Virtualmin hosting</p>
                            <span class="menuDesc"
                              >All our hosting accounts allow you to install
                              popular software such</span
                            >
                          </div>
                        </a>
                      </li>
                      <li class="dropMenuItems">
                        <a href="">
                          <div class="dropItemWrap">
                            <p class="menuName">Virtualmin hosting</p>
                            <span class="menuDesc"
                              >All our hosting accounts allow you to install
                              popular software such</span
                            >
                          </div>
                        </a>
                      </li>
                      <li class="dropMenuItems">
                        <a href="">
                          <div class="dropItemWrap">
                            <p class="menuName">Virtualmin hosting</p>
                            <span class="menuDesc"
                              >All our hosting accounts allow you to install
                              popular software such</span
                            >
                          </div>
                        </a>
                      </li>
                      <li class="dropMenuItems">
                        <a href="">
                          <div class="dropItemWrap">
                            <p class="menuName">Virtualmin hosting</p>
                            <span class="menuDesc"
                              >All our hosting accounts allow you to install
                              popular software such</span
                            >
                          </div>
                        </a>
                      </li>
                      <li class="dropMenuItems">
                        <a href="">
                          <div class="dropItemWrap">
                            <p class="menuName">Virtualmin hosting</p>
                            <span class="menuDesc"
                              >All our hosting accounts allow you to install
                              popular software such</span
                            >
                          </div>
                        </a>
                      </li>
                      <li class="dropMenuItems">
                        <a href="">
                          <div class="dropItemWrap">
                            <p class="menuName">Virtualmin hosting</p>
                            <span class="menuDesc"
                              >All our hosting accounts allow you to install
                              popular software such</span
                            >
                          </div>
                        </a>
                      </li>
                    </ul>
                  </div>
                </li>
                <li class="nav-item">
                  <a class="nav-link" aria-current="page" href="#"
                    >Help Center</a
                  >
                </li>
              </ul>
            </div>
          </div>
        </nav>
      </header>
      <!-- Navbar End -->

      <!-- TopbarText Start -->
      <section id="bannerSection">
        <div class="bannerWrapper">
          <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="bannerContentWrapper bannerText">
              <h1>Login to your account</h1>
              <p>
                All our hosting accounts allow you to install popular software
                such
              </p>
            </div>
          </div>
        </div>
      </section>
      <!-- TopBarText End -->

      <!-- BannerImg start -->
      <div class="bannerArrow"></div>
      <div class="bannerServer">
        <img src="/assets/images/solutions/sever.png" alt="">
      </div>
      <!-- BannerImg End -->
    </section>
    <!-- Topbar End -->

    <!-- Product and Services Section Start -->
    <section id="regSection">
      <div class="regRow">
       
        <div class="col-lg-10 col-12">
          <div class="reg-form-wrap">
            <div class="reg-title-wrap">
              <h2 class="sectionTitle">Register <span>Now</span></h2>
              <p class="secText">
                Please enter your details to signup. | <span>Already have an account? <a href="" class="bg-primary">Sign in</a></span>
              </p>
            </div>
            <form action="">
              <div class="reg-form">
                <div class="col-md-6 col-12">
                  <div class="reg-left">
                    <div class="c-name-wrap">
                      <label for="">Company Name</label>
                      <input type="text">
                    </div>
                    <div class="f-name-wrap">
                      <label for="">Full Name</label>
                      <input type="text" >
                    </div>
                    <div class="username-wrap">
                      <label for="">Username</label>
                      <input type="text">
                    </div>
                    <div class="email-wrap">
                      <label for="">Email</label>
                      <input type="email" name="" id="">
                    </div>
                    <div class="pass-wrap">
                      <label for="">Password</label>
                      <input type="password" name="" id="">
                    </div>
                    <div class="confirm-pass-wrap">
                      <label for="">Confirm Password</label>
                      <input type="password" name="" id="">
                    </div>
                  </div>
                </div>
                <div class="col-md-6 col-12">
                  <div class="reg-right">
                    <div class="address-wrap">
                      <label for="">Address</label>
                      <input type="text">
                    </div>
                    <div class="city-wrap">
                      <label for="">City</label>
                      <input type="text" >
                    </div>
                    <div class="state-wrap">
                      <label for="">State/Province</label>
                      <input type="text">
                    </div>
                    <div class="postal-wrap">
                      <label for="">Zip/Postal Code</label>
                      <input type="text" name="" id="">
                    </div>
                    <div class="country-wrap">
                      <label for="">Country</label>
                      <select name="" id="">
                        <option value="">Dharavi</option>
                        <option value="">Lokhandwala</option>
                      </select>
                    </div>
                    <div class="phone-wrap">
                      <label for="">Phone</label>
                      <input type="text" name="" id="">
                    </div>
                  </div>
                </div>
              </div>
              <div class="submit-wrap">
                <button class="btn btn-lg btn-primary">Sign Up</button>
              </div>
            </form>
          </div>
        </div>
        
      </div>
    </section>
    <!-- Product and Services Section End -->
	  <!-- Footer Section Start -->
    <footer id="footerSection">
      <div class="container-fluid px-0">
        <div class="footerRow">
          <div class="col-xl-6 col-lg-12 col-md-12">
            <div class="foolinksWrap">
              <ul>
                <li><a href="" target="_blank"> Home</a></li>
                <li><a href="" target="_blank"> Hosting</a></li>
                <li><a href="" target="_blank"> Domains</a></li>
                <li><a href="" target="_blank"> Affiliate</a></li>
                <li><a href="" target="_blank"> About Us</a></li>
                <li><a href="" target="_blank"> Help Center</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="copyWriteSection">
          <div class="copyWriteRow">
            <div class="col-lg-5 col-md-12">
              <div class="copyWriteContent">
                <p>
                  © 2023 MadPopo, Designed by
                  <a href="https://version-next.com/"
                    >Version Next Technologies</a
                  >.
                </p>
              </div>
            </div>
            <div class="col-lg-5 col-md-12">
              <div class="socialIconsWrap">
                <ul>
                  <li>
                    <a href="" target="_blank">
                      <span>
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          width="8"
                          height="14"
                          viewBox="0 0 8 14"
                          fill="none"
                        >
                          <g clip-path="url(#clip0_1179_13028)">
                            <path
                              d="M7.529 0.100962V2.32212H6.20808C5.72571 2.32212 5.40039 2.42308 5.23212 2.625C5.06385 2.82692 4.97972 3.12981 4.97972 3.53365V5.1238H7.44486L7.11674 7.61418H4.97972V14H2.4052V7.61418H0.259766V5.1238H2.4052V3.28966C2.4052 2.24639 2.69686 1.4373 3.2802 0.86238C3.86353 0.28746 4.64037 0 5.61073 0C6.43525 0 7.07467 0.0336538 7.529 0.100962Z"
                              fill="#D9D9D9"
                            />
                          </g>
                          <defs>
                            <clipPath id="clip0_1179_13028">
                              <rect width="7.53846" height="14" fill="white" />
                            </clipPath>
                          </defs>
                        </svg>
                      </span>
                    </a>
                  </li>
                  <li>
                    <a href="" target="_blank">
                      <span>
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          width="19"
                          height="14"
                          viewBox="0 0 19 14"
                          fill="none"
                        >
                          <g clip-path="url(#clip0_1179_13024)">
                            <path
                              d="M17.9074 1.6625C17.4189 2.37708 16.8283 2.98594 16.1355 3.48906C16.1428 3.59115 16.1465 3.74427 16.1465 3.94844C16.1465 4.89635 16.0079 5.84245 15.7309 6.78672C15.4538 7.73099 15.0327 8.63698 14.4676 9.50469C13.9025 10.3724 13.2298 11.1398 12.4496 11.807C11.6694 12.4742 10.7288 13.0065 9.62773 13.4039C8.52669 13.8013 7.34909 14 6.09492 14C4.11888 14 2.31055 13.4714 0.669922 12.4141C0.92513 12.4432 1.20951 12.4578 1.52305 12.4578C3.16367 12.4578 4.62565 11.9547 5.90898 10.9484C5.14336 10.9339 4.45794 10.6987 3.85273 10.243C3.24753 9.78724 2.8319 9.20573 2.60586 8.49844C2.84648 8.5349 3.06888 8.55312 3.27305 8.55312C3.58659 8.55312 3.89648 8.51302 4.20273 8.43281C3.38607 8.2651 2.70977 7.85859 2.17383 7.21328C1.63789 6.56797 1.36992 5.81875 1.36992 4.96562V4.92188C1.86576 5.19896 2.39805 5.34844 2.9668 5.37031C2.48555 5.04948 2.10273 4.63021 1.81836 4.1125C1.53398 3.59479 1.3918 3.03333 1.3918 2.42812C1.3918 1.78646 1.55221 1.19219 1.87305 0.645312C2.75534 1.73177 3.82904 2.6013 5.09414 3.25391C6.35924 3.90651 7.71367 4.26927 9.15742 4.34219C9.09909 4.0651 9.06992 3.79531 9.06992 3.53281C9.06992 2.55573 9.41445 1.72266 10.1035 1.03359C10.7926 0.344531 11.6257 0 12.6027 0C13.6236 0 14.484 0.371875 15.184 1.11563C15.9788 0.9625 16.7262 0.678125 17.4262 0.2625C17.1564 1.10104 16.6387 1.75 15.873 2.20937C16.5512 2.13646 17.2293 1.95417 17.9074 1.6625Z"
                              fill="#D9D9D9"
                            />
                          </g>
                          <defs>
                            <clipPath id="clip0_1179_13024">
                              <rect
                                width="17.5"
                                height="14"
                                fill="white"
                                transform="translate(0.537842)"
                              />
                            </clipPath>
                          </defs>
                        </svg>
                      </span>
                    </a>
                  </li>
                  <li>
                    <a href="" target="_blank">
                      <span>
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          width="15"
                          height="14"
                          viewBox="0 0 15 14"
                          fill="none"
                        >
                          <g clip-path="url(#clip0_1179_13030)">
                            <path
                              d="M9.37117 7C9.37117 6.3559 9.14331 5.80599 8.68758 5.35026C8.23185 4.89453 7.68194 4.66667 7.03784 4.66667C6.39374 4.66667 5.84383 4.89453 5.3881 5.35026C4.93237 5.80599 4.70451 6.3559 4.70451 7C4.70451 7.6441 4.93237 8.19401 5.3881 8.64974C5.84383 9.10547 6.39374 9.33333 7.03784 9.33333C7.68194 9.33333 8.23185 9.10547 8.68758 8.64974C9.14331 8.19401 9.37117 7.6441 9.37117 7ZM10.629 7C10.629 7.99653 10.2796 8.84418 9.58081 9.54297C8.88203 10.2418 8.03437 10.5911 7.03784 10.5911C6.04131 10.5911 5.19366 10.2418 4.49487 9.54297C3.79609 8.84418 3.4467 7.99653 3.4467 7C3.4467 6.00347 3.79609 5.15582 4.49487 4.45703C5.19366 3.75825 6.04131 3.40885 7.03784 3.40885C8.03437 3.40885 8.88203 3.75825 9.58081 4.45703C10.2796 5.15582 10.629 6.00347 10.629 7ZM11.6134 3.26302C11.6134 3.49392 11.5313 3.69141 11.3673 3.85547C11.2032 4.01953 11.0057 4.10156 10.7748 4.10156C10.5439 4.10156 10.3464 4.01953 10.1824 3.85547C10.0183 3.69141 9.93628 3.49392 9.93628 3.26302C9.93628 3.03212 10.0183 2.83464 10.1824 2.67057C10.3464 2.50651 10.5439 2.42448 10.7748 2.42448C11.0057 2.42448 11.2032 2.50651 11.3673 2.67057C11.5313 2.83464 11.6134 3.03212 11.6134 3.26302ZM7.03784 1.25781C6.99531 1.25781 6.76289 1.25629 6.34058 1.25326C5.91827 1.25022 5.59774 1.25022 5.37899 1.25326C5.16024 1.25629 4.86705 1.26541 4.49943 1.2806C4.13181 1.29579 3.81887 1.32617 3.56063 1.37174C3.30238 1.41732 3.08515 1.47352 2.90894 1.54036C2.60512 1.66189 2.33775 1.83811 2.10685 2.06901C1.87595 2.29991 1.69973 2.56727 1.57821 2.87109C1.51137 3.04731 1.45516 3.26454 1.40959 3.52279C1.36401 3.78103 1.33363 4.09397 1.31844 4.46159C1.30325 4.82921 1.29414 5.1224 1.2911 5.34115C1.28806 5.5599 1.28806 5.88042 1.2911 6.30273C1.29414 6.72504 1.29565 6.95747 1.29565 7C1.29565 7.04253 1.29414 7.27496 1.2911 7.69727C1.28806 8.11957 1.28806 8.4401 1.2911 8.65885C1.29414 8.8776 1.30325 9.17079 1.31844 9.53841C1.33363 9.90603 1.36401 10.219 1.40959 10.4772C1.45516 10.7355 1.51137 10.9527 1.57821 11.1289C1.69973 11.4327 1.87595 11.7001 2.10685 11.931C2.33775 12.1619 2.60512 12.3381 2.90894 12.4596C3.08515 12.5265 3.30238 12.5827 3.56063 12.6283C3.81887 12.6738 4.13181 12.7042 4.49943 12.7194C4.86705 12.7346 5.16024 12.7437 5.37899 12.7467C5.59774 12.7498 5.91827 12.7498 6.34058 12.7467C6.76289 12.7437 6.99531 12.7422 7.03784 12.7422C7.08038 12.7422 7.3128 12.7437 7.73511 12.7467C8.15742 12.7498 8.47795 12.7498 8.6967 12.7467C8.91545 12.7437 9.20863 12.7346 9.57625 12.7194C9.94387 12.7042 10.2568 12.6738 10.5151 12.6283C10.7733 12.5827 10.9905 12.5265 11.1667 12.4596C11.4706 12.3381 11.7379 12.1619 11.9688 11.931C12.1997 11.7001 12.3759 11.4327 12.4975 11.1289C12.5643 10.9527 12.6205 10.7355 12.6661 10.4772C12.7117 10.219 12.7421 9.90603 12.7572 9.53841C12.7724 9.17079 12.7815 8.8776 12.7846 8.65885C12.7876 8.4401 12.7876 8.11957 12.7846 7.69727C12.7815 7.27496 12.78 7.04253 12.78 7C12.78 6.95747 12.7815 6.72504 12.7846 6.30273C12.7876 5.88042 12.7876 5.5599 12.7846 5.34115C12.7815 5.1224 12.7724 4.82921 12.7572 4.46159C12.7421 4.09397 12.7117 3.78103 12.6661 3.52279C12.6205 3.26454 12.5643 3.04731 12.4975 2.87109C12.3759 2.56727 12.1997 2.29991 11.9688 2.06901C11.7379 1.83811 11.4706 1.66189 11.1667 1.54036C10.9905 1.47352 10.7733 1.41732 10.5151 1.37174C10.2568 1.32617 9.94387 1.29579 9.57625 1.2806C9.20863 1.26541 8.91545 1.25629 8.6967 1.25326C8.47795 1.25022 8.15742 1.25022 7.73511 1.25326C7.3128 1.25629 7.08038 1.25781 7.03784 1.25781ZM14.0378 7C14.0378 8.39149 14.0227 9.3546 13.9923 9.88932C13.9315 11.1532 13.5548 12.1315 12.8621 12.8242C12.1694 13.5169 11.1911 13.8937 9.92716 13.9544C9.39244 13.9848 8.42933 14 7.03784 14C5.64635 14 4.68324 13.9848 4.14852 13.9544C2.88463 13.8937 1.90633 13.5169 1.21362 12.8242C0.520915 12.1315 0.144179 11.1532 0.0834147 9.88932C0.0530328 9.3546 0.0378418 8.39149 0.0378418 7C0.0378418 5.60851 0.0530328 4.6454 0.0834147 4.11068C0.144179 2.84679 0.520915 1.86849 1.21362 1.17578C1.90633 0.483073 2.88463 0.106337 4.14852 0.0455729C4.68324 0.015191 5.64635 0 7.03784 0C8.42933 0 9.39244 0.015191 9.92716 0.0455729C11.1911 0.106337 12.1694 0.483073 12.8621 1.17578C13.5548 1.86849 13.9315 2.84679 13.9923 4.11068C14.0227 4.6454 14.0378 5.60851 14.0378 7Z"
                              fill="#D9D9D9"
                            />
                          </g>
                          <defs>
                            <clipPath id="clip0_1179_13030">
                              <rect
                                width="14"
                                height="14"
                                fill="white"
                                transform="translate(0.0378418)"
                              />
                            </clipPath>
                          </defs>
                        </svg>
                      </span>
                    </a>
                  </li>
                  <li>
                    <a href="" target="_blank">
                      <span>
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          width="15"
                          height="14"
                          viewBox="0 0 15 14"
                          fill="none"
                        >
                          <g clip-path="url(#clip0_1179_13026)">
                            <path
                              d="M3.28651 4.62693V13.8517H0.214704V4.62693H3.28651ZM3.48199 1.77852C3.4882 2.23154 3.3315 2.61008 3.01191 2.91416C2.69232 3.21824 2.27188 3.37028 1.75061 3.37028H1.73199C1.22313 3.37028 0.813551 3.21824 0.503267 2.91416C0.192984 2.61008 0.0378418 2.23154 0.0378418 1.77852C0.0378418 1.3193 0.197638 0.939205 0.51723 0.63823C0.836822 0.337255 1.25415 0.186768 1.76923 0.186768C2.2843 0.186768 2.69697 0.337255 3.00726 0.63823C3.31754 0.939205 3.47579 1.3193 3.48199 1.77852ZM14.3357 8.56443V13.8517H11.2732V8.91815C11.2732 8.26655 11.1476 7.75614 10.8962 7.3869C10.6449 7.01766 10.2524 6.83304 9.71869 6.83304C9.32774 6.83304 9.00039 6.94009 8.73665 7.15419C8.47291 7.36828 8.27588 7.63358 8.14556 7.95006C8.07729 8.13623 8.04316 8.38756 8.04316 8.70405V13.8517H4.98066C4.99307 11.3756 4.99928 9.36806 4.99928 7.82905C4.99928 6.29005 4.99618 5.37161 4.98997 5.07374L4.98066 4.62693H8.04316V5.96735H8.02455C8.14866 5.76877 8.27588 5.59501 8.40619 5.44608C8.53651 5.29714 8.71182 5.13579 8.93213 4.96203C9.15243 4.78827 9.42237 4.6533 9.74197 4.55711C10.0616 4.46093 10.4168 4.41283 10.8078 4.41283C11.869 4.41283 12.7222 4.765 13.3676 5.46935C14.013 6.17369 14.3357 7.20538 14.3357 8.56443Z"
                              fill="#D9D9D9"
                            />
                          </g>
                          <defs>
                            <clipPath id="clip0_1179_13026">
                              <rect
                                width="14.2979"
                                height="14"
                                fill="white"
                                transform="translate(0.0378418)"
                              />
                            </clipPath>
                          </defs>
                        </svg>
                      </span>
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </footer>
    <!-- Footer Section End -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script src="./assets/js/main.js"></script>
  </body>
</html>