import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';

import '../data/auth_repository.dart';
import '../domain/current_user.dart';

class SessionState {
  const SessionState({
    this.isLoading = false,
    this.accessToken,
    this.currentUser,
  });

  final bool isLoading;
  final String? accessToken;
  final CurrentUser? currentUser;

  bool get isAuthenticated => accessToken != null && accessToken!.isNotEmpty;
  String? get userName => currentUser?.name;

  SessionState copyWith({
    bool? isLoading,
    String? accessToken,
    CurrentUser? currentUser,
    bool clearToken = false,
    bool clearUser = false,
  }) {
    return SessionState(
      isLoading: isLoading ?? this.isLoading,
      accessToken: clearToken ? null : accessToken ?? this.accessToken,
      currentUser: clearUser ? null : currentUser ?? this.currentUser,
    );
  }
}

class SessionNotifier extends Notifier<SessionState> {
  static const String _tokenKey = 'saico_mobile_token';
  final FlutterSecureStorage _storage = const FlutterSecureStorage();

  @override
  SessionState build() {
    _bootstrap();
    return const SessionState(isLoading: true);
  }

  Future<void> _bootstrap() async {
    final storedToken = await _storage.read(key: _tokenKey);

    if (storedToken == null || storedToken.isEmpty) {
      state = state.copyWith(isLoading: false, clearToken: true, clearUser: true);
      return;
    }

    state = state.copyWith(isLoading: true, accessToken: storedToken);

    try {
      final user = await ref.read(authRepositoryProvider).fetchProfile();
      if (user == null) {
        await closeSession();
        return;
      }

      state = state.copyWith(
        isLoading: false,
        accessToken: storedToken,
        currentUser: user,
      );
    } catch (_) {
      await closeSession();
    }
  }

  Future<void> openSession({
    required String accessToken,
    required CurrentUser user,
  }) async {
    await _storage.write(key: _tokenKey, value: accessToken);
    state = state.copyWith(
      isLoading: false,
      accessToken: accessToken,
      currentUser: user,
    );
  }

  Future<void> closeSession() async {
    await _storage.delete(key: _tokenKey);
    state = state.copyWith(
      isLoading: false,
      clearToken: true,
      clearUser: true,
    );
  }
}

final sessionProvider = NotifierProvider<SessionNotifier, SessionState>(SessionNotifier.new);
