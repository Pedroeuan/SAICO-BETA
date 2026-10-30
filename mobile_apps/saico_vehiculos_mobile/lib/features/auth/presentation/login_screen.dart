import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';

import '../../../core/layout/adaptive_page_scaffold.dart';
import '../../../core/network/network_error_formatter.dart';
import '../../../core/widgets/app_button.dart';
import '../../../core/widgets/app_card.dart';
import '../../../core/widgets/saico_brand_header.dart';
import '../application/auth_controller.dart';
import '../application/session_provider.dart';

class LoginScreen extends ConsumerStatefulWidget {
  const LoginScreen({super.key});

  @override
  ConsumerState<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends ConsumerState<LoginScreen> {
  final GlobalKey<FormState> _formKey = GlobalKey<FormState>();
  final TextEditingController _emailController = TextEditingController();
  final TextEditingController _passwordController = TextEditingController();
  bool _hidePassword = true;

  @override
  void dispose() {
    _emailController.dispose();
    _passwordController.dispose();
    super.dispose();
  }

  Future<void> _submit() async {
    if (!_formKey.currentState!.validate()) {
      return;
    }

    HapticFeedback.selectionClick();
    await ref.read(authControllerProvider.notifier).login(
          email: _emailController.text.trim(),
          password: _passwordController.text.trim(),
        );

    if (mounted && !ref.read(authControllerProvider).hasError) {
      context.go('/home');
    }
  }

  @override
  Widget build(BuildContext context) {
    final authState = ref.watch(authControllerProvider);
    final session = ref.watch(sessionProvider);
    final isLoading = authState.isLoading;
    final size = MediaQuery.sizeOf(context);

    if (!session.isLoading && session.isAuthenticated) {
      WidgetsBinding.instance.addPostFrameCallback((_) {
        if (mounted) {
          context.go('/home');
        }
      });
    }

    return AdaptivePageScaffold(
      title: 'SAICO',
      child: session.isLoading
          ? const Center(child: CircularProgressIndicator())
          : ListView(
              children: <Widget>[
                SaicoBrandHeader(
                  title: 'Vehículos',
                  subtitle: '',
                ),
                const SizedBox(height: 20),
                Center(
                  child: ConstrainedBox(
                    constraints: const BoxConstraints(maxWidth: 420),
                    child: AppCard(
                      child: Padding(
                        padding: EdgeInsets.all(size.width < 600 ? 0 : 12),
                        child: Form(
                          key: _formKey,
                          child: Column(
                            mainAxisSize: MainAxisSize.min,
                            crossAxisAlignment: CrossAxisAlignment.stretch,
                            children: <Widget>[
                              Text(
                                'Iniciar sesión',
                                style: Theme.of(context).textTheme.titleLarge,
                              ),
                              const SizedBox(height: 18),
                              TextFormField(
                                controller: _emailController,
                                keyboardType: TextInputType.emailAddress,
                                decoration: const InputDecoration(
                                    labelText: 'Correo',
                                    prefixIcon: Icon(Icons.mail_outline)),
                                validator: (value) {
                                  if (value == null || value.trim().isEmpty) {
                                    return 'Ingresa tu correo.';
                                  }
                                  return null;
                                },
                              ),
                              const SizedBox(height: 16),
                              TextFormField(
                                controller: _passwordController,
                                obscureText: _hidePassword,
                                decoration: InputDecoration(
                                  labelText: 'Contraseña',
                                  prefixIcon: const Icon(Icons.lock_outline),
                                  suffixIcon: IconButton(
                                    tooltip: _hidePassword
                                        ? 'Mostrar contraseña'
                                        : 'Ocultar contraseña',
                                    onPressed: () {
                                      setState(
                                          () => _hidePassword = !_hidePassword);
                                    },
                                    icon: Icon(
                                      _hidePassword
                                          ? Icons.visibility_outlined
                                          : Icons.visibility_off_outlined,
                                    ),
                                  ),
                                ),
                                validator: (value) {
                                  if (value == null || value.trim().isEmpty) {
                                    return 'Ingresa tu contrasena.';
                                  }
                                  return null;
                                },
                              ),
                              const SizedBox(height: 20),
                              AppButton(
                                label: isLoading ? 'Ingresando...' : 'Ingresar',
                                icon: Icons.login,
                                isLoading: isLoading,
                                onPressed: _submit,
                              ),
                              if (authState.hasError) ...<Widget>[
                                const SizedBox(height: 14),
                                Text(
                                  formatAppError(authState.error!),
                                  style: TextStyle(
                                    color: Theme.of(context).colorScheme.error,
                                    fontWeight: FontWeight.w500,
                                  ),
                                ),
                              ],
                            ],
                          ),
                        ),
                      ),
                    ),
                  ),
                ),
              ],
            ),
    );
  }
}
