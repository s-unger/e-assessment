# e-assessment
Our Project features some parts of an e-assessment-system. For demonstration we made an elementary school math course. The following features are shown in the project:

* different item-types and question-generation
* learning-analytics, individual feedback and visualisation
* Learning-Mode in Browser and VR
* Exam-Mode in VR for privacy-friendly proctering
* user management and progress report
* some design

## Technology

This project uses a LAMP-Stack, as well as a standalone-windows-vr-application, that supports different vr glasses made by Unreal 4.

## VR-App

The VR-App is one of the first one-page-browser-vr-applications in the world. It only shows this e-assessment page and nothing else. It is possible to use it via mouse and keyboard.

## Security

The LAMP-Stack is secured against SQL-Injection-Attacks. The VR-Proctoring is not that good secured, and should only be seen as a tech-demo. Later on this can be refined for production.

## Feedback

The user gets information about correctness, points and grade. Also there is specific feedback for some common misstakes.

## Visualisation

Ready for test, progress per task, compare yourself with the group, correctness percentage, performance per skill.

## Continuous Integration

We used continuous integration for this project. When pushing to any branch, the files will be automatically uploaded to the webspace for using and testing them there. The URLs for viewing are:

  * http://e-assessment.bplaced.net/main/ for Branch main
  * http://e-assessment.bplaced.net/sebastian/ for Branch sebastian
  * http://e-assessment.bplaced.net/sophia/ for Branch sophia
  * http://e-assessment.bplaced.net/stefanie/ for Branch stefanie
  * http://e-assessment.bplaced.net/yoanna/ for Branch yoanna
