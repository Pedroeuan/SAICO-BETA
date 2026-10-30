import 'package:flutter/material.dart';

class SaicoPageTransition extends PageTransitionsBuilder {
  const SaicoPageTransition();
  @override
  Widget buildTransitions<T>(
      PageRoute<T> route,
      BuildContext context,
      Animation<double> animation,
      Animation<double> secondaryAnimation,
      Widget child) {
    if (MediaQuery.disableAnimationsOf(context)) return child;
    final eased =
        CurvedAnimation(parent: animation, curve: Curves.easeOutCubic);
    return FadeTransition(
        opacity: eased,
        child: SlideTransition(
            position:
                Tween<Offset>(begin: const Offset(0, 0.025), end: Offset.zero)
                    .animate(eased),
            child: child));
  }
}
